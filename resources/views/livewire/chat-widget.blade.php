<div x-data="{ open: false, bubble: false }"
     x-init="try { if (!sessionStorage.getItem('cwSeen')) { setTimeout(() => { if (!open) bubble = true }, 4000) } } catch (e) {}"
     style="position:fixed;right:1.25rem;bottom:1.25rem;z-index:1050;">
    <style>
        [x-cloak]{display:none !important}
        .cw-panel{width:min(360px,calc(100vw - 2.5rem));height:min(480px,70vh);background:#070719;border:1px solid rgba(34,211,238,.35);border-radius:1rem;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,.5);margin-bottom:.75rem}
        .cw-head{background:#0b0b26;padding:.75rem 1rem;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid rgba(34,211,238,.2);color:#fff;font-weight:600}
        .cw-tools{display:flex;align-items:center;gap:.6rem;font-size:.8rem;font-weight:400}
        .cw-tools a,.cw-tools button.cw-link{color:#22d3ee;background:none;border:0;padding:0;text-decoration:none;cursor:pointer}
        .cw-tools a:hover,.cw-tools button.cw-link:hover{text-decoration:underline}
        .cw-body{flex:1;overflow-y:auto;padding:1rem;display:flex;flex-direction:column;gap:.6rem}
        .cw-msg{max-width:85%;padding:.55rem .8rem;border-radius:.9rem;font-size:.92rem;white-space:pre-line;overflow-wrap:anywhere}
        .cw-bot{background:#16162f;color:#e5e7eb;align-self:flex-start}
        .cw-user{background:#22d3ee;color:#04141a;align-self:flex-end}
        .cw-foot{padding:.6rem;border-top:1px solid rgba(34,211,238,.2);background:#0b0b26}
        .cw-fab{width:56px;height:56px;border-radius:50%;border:0;background:#22d3ee;color:#04141a;font-size:1.5rem;box-shadow:0 6px 24px rgba(34,211,238,.4);float:right;padding:0}
        .cw-avatar{width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #22d3ee;flex-shrink:0;background:#22d3ee;color:#04141a;display:inline-flex;align-items:center;justify-content:center;font-weight:700}
        .cw-row{display:flex;gap:.5rem;align-items:flex-end;align-self:flex-start;max-width:92%}
        .cw-row .cw-msg{align-self:auto}
        .cw-fab img{width:100%;height:100%;border-radius:50%;object-fit:cover}
        .cw-chips{display:flex;flex-wrap:wrap;gap:.4rem;padding-left:2.6rem}
        .cw-chip{background:transparent;border:1px solid #22d3ee;color:#22d3ee;border-radius:2rem;padding:.25rem .75rem;font-size:.82rem;cursor:pointer}
        .cw-chip:hover:not(:disabled){background:#22d3ee;color:#04141a}
        .cw-bubble{position:absolute;right:0;bottom:68px;background:#fff;color:#111827;padding:.5rem .9rem;border-radius:.9rem .9rem 0 .9rem;font-size:.88rem;box-shadow:0 6px 24px rgba(0,0,0,.35);white-space:nowrap;cursor:pointer}
    </style>

    <div x-show="open" x-cloak x-transition class="cw-panel" role="dialog" aria-label="Asistente virtual">
        <div class="cw-head">
            <span class="d-flex align-items-center gap-2">
                <img src="{{ asset('img/yenny.jpg') }}" alt="Yenny" class="cw-avatar" onerror="this.outerHTML='<span class=\'cw-avatar\'>Y</span>'">
                Asistente de Yenny
            </span>
            <span class="cw-tools">
                <button type="button" class="cw-link" wire:click="clear">Limpiar</button>
                <a href="#contact" @click="open=false">Contacto</a>
                <button type="button" @click="open=false" class="btn-close btn-close-white" aria-label="Cerrar"></button>
            </span>
        </div>

        <div class="cw-body" x-ref="box"
             x-init="new MutationObserver(() => { $refs.box.scrollTop = $refs.box.scrollHeight }).observe($refs.box,{childList:true,subtree:true})">
            @foreach ($messages as $m)
                @if ($m['role'] === 'user')
                    <div class="cw-msg cw-user">{{ $m['content'] }}</div>
                @else
                    <div class="cw-row">
                        <img src="{{ asset('img/yenny.jpg') }}" alt="" class="cw-avatar" onerror="this.outerHTML='<span class=\'cw-avatar\'>Y</span>'">
                        <div class="cw-msg cw-bot">{{ $m['content'] }}</div>
                    </div>
                @endif
            @endforeach

            @if (count($messages) === 1 && ! $pending)
                <div class="cw-chips">
                    @foreach ($suggestions as $i => $label)
                        <button type="button" class="cw-chip" wire:click="ask({{ $i }})">{{ $label }}</button>
                    @endforeach
                </div>
            @endif

            @if ($pending)
                <div class="cw-msg cw-bot" x-init="$wire.reply()">Escribiendo...</div>
            @endif
        </div>

        <form wire:submit="send" class="cw-foot">
            @error('input') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
            <div class="input-group">
                <input type="text" wire:model="input" maxlength="500" class="form-control" placeholder="Escriba su consulta" autocomplete="off" @disabled($pending)>
                <button class="btn btn-primary" type="submit" @disabled($pending)>Enviar</button>
            </div>
        </form>
    </div>

    <div x-show="bubble && !open" x-cloak x-transition class="cw-bubble"
         @click="open=true; bubble=false; try { sessionStorage.setItem('cwSeen','1') } catch (e) {}">
        ¿Le puedo ayudar?
    </div>

    <button type="button" class="cw-fab"
            @click="open=!open; bubble=false; try { sessionStorage.setItem('cwSeen','1') } catch (e) {}"
            aria-label="Abrir chat">
        <img src="{{ asset('img/yenny.jpg') }}" alt="Chat" onerror="this.replaceWith(Object.assign(document.createElement('i'),{className:'bi bi-chat-dots-fill'}))">
    </button>
</div>
