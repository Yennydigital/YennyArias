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
