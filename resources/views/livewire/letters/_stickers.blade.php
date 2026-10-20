@php
    $stkPacks = \App\Models\StickerPack::where('is_active', true)->with('stickers')->orderBy('sort')->orderBy('id')->get();
    $stkTier = \App\Support\Stickers::tierOf(auth()->user());
@endphp
@if ($stkPacks->count())
<div class="stk-tray" wire:ignore x-data="{ open: {{ $stkPacks->first()->id }} }">
    <style>
        .stk-tabs{display:flex;gap:6px;overflow-x:auto;margin-bottom:8px}
        .stk-tabs button{border:1px solid rgba(148,163,184,.3);background:none;color:#94a3b8;padding:5px 12px;border-radius:999px;font-size:13px;cursor:pointer;white-space:nowrap}
        .stk-tabs button.on{border-color:#22d3ee;color:#22d3ee}
        .stk-grid{display:flex;flex-wrap:wrap;gap:8px;max-height:150px;overflow-y:auto;padding:4px 0 10px}
        .stk-b{border:1px solid rgba(148,163,184,.2);background:rgba(15,15,45,.6);border-radius:10px;padding:4px;cursor:pointer}
        .stk-b img{display:block;height:52px;width:auto}
        .stk-lock{color:#94a3b8;font-size:13px}
    </style>
    <label>Stickers</label>
    <div class="stk-tabs">
        @foreach ($stkPacks as $pk)
            @php $ok = (\App\Support\Stickers::TIERS[$pk->min_plan] ?? 0) <= $stkTier; @endphp
            <button type="button" @click="open = {{ $pk->id }}" :class="open === {{ $pk->id }} ? 'on' : ''">{{ $pk->name }}@unless ($ok) &#128274;@endunless</button>
        @endforeach
    </div>
    @foreach ($stkPacks as $pk)
        @php $ok = (\App\Support\Stickers::TIERS[$pk->min_plan] ?? 0) <= $stkTier; @endphp
        <div class="stk-grid" x-show="open === {{ $pk->id }}" x-cloak>
            @if ($ok)
                @foreach ($pk->stickers as $st)
                    <button type="button" class="stk-b" onmousedown="event.preventDefault()" onclick="umbraInsertSticker({{ $st->id }})"><img src="{{ $st->url }}" alt=""></button>
                @endforeach
            @else
                <a class="stk-lock" href="{{ route('plans.index') }}">This pack is included from the {{ ucfirst($pk->min_plan) }} plan. See plans</a>
            @endif
        </div>
    @endforeach
</div>
@script
<script>
    if (typeof window.umbraInsertSticker === 'undefined') {
        var last = null;
        document.addEventListener('focusin', function (e) {
            if (e.target.tagName === 'TEXTAREA' && e.target.closest('.wz')) { last = e.target; }
        });
        window.umbraInsertSticker = function (id) {
            var el = last || document.querySelector('.wz textarea');
            if (el === null) { return; }
            var mark = '[[s:' + id + ']]';
            var s = el.selectionStart == null ? el.value.length : el.selectionStart;
            var e = el.selectionEnd == null ? s : el.selectionEnd;
            el.value = el.value.slice(0, s) + mark + el.value.slice(e);
            el.selectionStart = el.selectionEnd = s + mark.length;
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.focus();
        };
    }
</script>
@endscript
@endif

<div class="gif-picker" wire:ignore data-key="{{ config('services.giphy.key') }}" data-mode="gifs">
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <button type="button" data-gif-toggle
                style="padding:6px 14px;border:1px solid currentColor;border-radius:999px;background:none;color:inherit;cursor:pointer">GIF</button>
    </div>

    <div data-gif-panel style="display:none;margin-top:8px">
        <div style="display:flex;gap:6px;margin-bottom:6px">
            <button type="button" data-gif-mode="gifs"
                    style="padding:4px 12px;border:1px solid currentColor;border-radius:999px;background:none;color:inherit;cursor:pointer">GIFs</button>
            <button type="button" data-gif-mode="stickers"
                    style="padding:4px 12px;border:1px solid currentColor;border-radius:999px;background:none;color:inherit;cursor:pointer;opacity:.5">Stickers</button>
        </div>
        <input type="text" data-gif-q placeholder="Buscar GIF..." autocomplete="off">
        <div data-gif-msg style="margin-top:6px;font-size:12px"></div>
        <div data-gif-grid style="display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:6px;margin-top:8px;max-height:260px;overflow:auto"></div>
        <small>Powered by GIPHY</small>
    </div>
</div>

@script
<script>
if (!window.__umbraGif) {
    window.__umbraGif = true;
    let lastTextarea = null;
    let timer = null;

    document.addEventListener('focusin', function (e) {
        if (e.target && e.target.tagName === 'TEXTAREA') lastTextarea = e.target;
    });

    async function gifSearch(root) {
        const key = root.dataset.key;
        const kind = root.dataset.mode === 'stickers' ? 'stickers' : 'gifs';
        const msg = root.querySelector('[data-gif-msg]');
        const grid = root.querySelector('[data-gif-grid]');
        const term = root.querySelector('[data-gif-q]').value.trim();

        if (!key) { msg.textContent = 'Falta la API key (data-key vacio)'; return; }
        msg.textContent = 'Cargando...';

        const url = 'https://api.giphy.com/v1/' + kind + '/' + (term ? 'search' : 'trending')
            + '?api_key=' + encodeURIComponent(key)
            + '&limit=24&rating=g&lang=es'
            + (term ? '&q=' + encodeURIComponent(term) : '');

        try {
            const r = await fetch(url);
            if (!r.ok) { grid.innerHTML = ''; msg.textContent = 'Error de GIPHY: ' + r.status; return; }
            const j = await r.json();
            const items = j.data || [];
            grid.innerHTML = '';
            items.forEach(function (g) {
                const img = document.createElement('img');
                img.src = g.images.fixed_width_small.url;
                img.loading = 'lazy';
                img.dataset.gifId = g.id;
                img.style.cssText = 'width:100%;cursor:pointer;border-radius:8px';
                grid.appendChild(img);
            });
            msg.textContent = items.length ? '' : 'Sin resultados';
        } catch (err) {
            console.error('GIF search error:', err);
            grid.innerHTML = '';
            msg.textContent = 'No se pudo conectar con GIPHY';
        }
    }

    document.addEventListener('click', function (e) {
        const modeBtn = e.target.closest('[data-gif-mode]');
        if (modeBtn) {
            const root = modeBtn.closest('.gif-picker');
            root.dataset.mode = modeBtn.dataset.gifMode;
            root.querySelectorAll('[data-gif-mode]').forEach(function (b) {
                b.style.opacity = (b === modeBtn) ? '1' : '.5';
            });
            root.querySelector('[data-gif-q]').placeholder =
                root.dataset.mode === 'stickers' ? 'Buscar sticker...' : 'Buscar GIF...';
            gifSearch(root);
            return;
        }

        const toggle = e.target.closest('[data-gif-toggle]');
        if (toggle) {
            const root = toggle.closest('.gif-picker');
            const panel = root.querySelector('[data-gif-panel]');
            const open = panel.style.display === 'none';
            panel.style.display = open ? 'block' : 'none';
            if (open && !root.querySelector('[data-gif-grid]').children.length) gifSearch(root);
            return;
        }

        const img = e.target.closest('[data-gif-id]');
        if (img) {
            const root = img.closest('.gif-picker');
            const t = lastTextarea || document.querySelector('textarea');
            if (!t) return;
            const tok = '[gif:' + img.dataset.gifId + ']';
            const s = (t.selectionStart !== undefined && t.selectionStart !== null) ? t.selectionStart : t.value.length;
            t.value = t.value.slice(0, s) + tok + t.value.slice(s);
            t.dispatchEvent(new Event('input', { bubbles: true }));
            t.focus();
            root.querySelector('[data-gif-panel]').style.display = 'none';
        }
    });

    document.addEventListener('input', function (e) {
        if (e.target && e.target.matches && e.target.matches('[data-gif-q]')) {
            const root = e.target.closest('.gif-picker');
            clearTimeout(timer);
            timer = setTimeout(function () { gifSearch(root); }, 400);
        }
    });
}
</script>
@endscript
