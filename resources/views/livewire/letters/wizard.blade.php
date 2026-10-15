<div x-data x-init="$wire.tz = Intl.DateTimeFormat().resolvedOptions().timeZone">
    <style>
        .wz{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:32px;align-items:start}
        @media(max-width:900px){.wz{grid-template-columns:1fr}}
        .wz-steps{display:flex;gap:8px;margin-bottom:20px;font-size:13px;opacity:.9}
        .wz-steps span{padding:4px 12px;border-radius:999px;border:1px solid rgba(255,255,255,.18)}
        .wz-steps .on{background:rgba(255,255,255,.14);font-weight:600}
        .wz label{display:block;margin:14px 0 6px;font-size:13px;opacity:.85}
        .wz input,.wz textarea,.wz select{width:100%}
        .wz-tpls{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
        .wz-tpl{padding:16px 10px;border-radius:12px;border:1px solid rgba(255,255,255,.18);background:none;color:inherit;cursor:pointer;font:inherit}
        .wz-tpl.on{border-color:#a78bfa;background:rgba(167,139,250,.15)}
        .wz-err{color:#f87171;font-size:12px;margin-top:4px}
        .wz-nav{display:flex;gap:10px;margin-top:22px}
        .wz-btn{padding:10px 18px;border-radius:10px;border:1px solid rgba(255,255,255,.2);background:none;color:inherit;cursor:pointer;font:inherit}
        .wz-btn.pri{background:#7c3aed;border-color:#7c3aed;color:#fff}
        .phone{width:300px;height:560px;margin:0 auto;border-radius:36px;border:8px solid #1f1f2b;background:#0e0e16;overflow:hidden;position:sticky;top:20px;box-shadow:0 20px 50px rgba(0,0,0,.5)}
        .phone-in{height:100%;overflow-y:auto;padding:36px 22px 24px;font-size:14px;line-height:1.6}
        .phone h2{font-size:22px;margin:6px 0 14px}
        .phone .tag{font-size:11px;letter-spacing:.12em;text-transform:uppercase;opacity:.6}
        .phone p{margin:0 0 14px;white-space:pre-line}
        .phone .muted{opacity:.35}
    </style>

    <div class="wz-steps">
        <span class="{{ $step === 1 ? 'on' : '' }}">1 · Template</span>
        <span class="{{ $step === 2 ? 'on' : '' }}">2 · Content</span>
        <span class="{{ $step === 3 ? 'on' : '' }}">3 · Seal</span>
    </div>

    <div class="wz">
        <div>
            @if ($step === 1)
                <h3>Choose a template</h3>
                <div class="wz-tpls">
                    @foreach ($templates as $key => $label)
                        <button type="button" wire:click="pickTemplate('{{ $key }}')"
                                class="wz-tpl {{ $template === $key ? 'on' : '' }}">{{ $label }}</button>
                    @endforeach
                </div>
            @elseif ($step === 2)
                <h3>Write your letter</h3>

                <label>Title</label>
                <input type="text" wire:model.live.debounce.250ms="title" maxlength="80" placeholder="For my favorite person">
                @error('title') <div class="wz-err">{{ $message }}</div> @enderror

                <label>To</label>
                <input type="text" wire:model.live.debounce.250ms="recipient" maxlength="60">
                @error('recipient') <div class="wz-err">{{ $message }}</div> @enderror

                <label>From</label>
                <input type="text" wire:model.live.debounce.250ms="sender" maxlength="60">
                @error('sender') <div class="wz-err">{{ $message }}</div> @enderror

                <label>What I love about you</label>
                <textarea rows="4" wire:model.live.debounce.250ms="love"></textarea>

                <label>Our story</label>
                <textarea rows="4" wire:model.live.debounce.250ms="story"></textarea>

                <label>Our dreams</label>
                <textarea rows="4" wire:model.live.debounce.250ms="dreams"></textarea>
                @error('love') <div class="wz-err">{{ $message }}</div> @enderror
                @error('story') <div class="wz-err">{{ $message }}</div> @enderror
                @error('dreams') <div class="wz-err">{{ $message }}</div> @enderror
            @else
                <h3>Seal your letter</h3>
                <p style="opacity:.7;font-size:13px">You will get a 12-character code. It is not stored anywhere, so keep it safe: without it nobody can open the letter.</p>

                <label>Opens on (optional)</label>
                <input type="datetime-local" wire:model="opens_at">
                @error('opens_at') <div class="wz-err">{{ $message }}</div> @enderror

                <label>Keep it available for</label>
                <select wire:model="keep">
                    <option value="7">7 days</option>
                    <option value="30">30 days</option>
                    <option value="90">90 days</option>
                    <option value="365">365 days</option>
                </select>
                @error('keep') <div class="wz-err">{{ $message }}</div> @enderror

                <label style="display:flex;gap:8px;align-items:center">
                    <input type="checkbox" wire:model="burn" style="width:auto"> Destroy after it is read
                </label>
            @endif

            <div class="wz-nav">
                @if ($step > 1)
                    <button type="button" class="wz-btn" wire:click="back">Back</button>
                @endif
                @if ($step < 3)
                    <button type="button" class="wz-btn pri" wire:click="next">Next</button>
                @else
                    <button type="button" class="wz-btn pri" wire:click="seal" wire:loading.attr="disabled">Seal letter</button>
                @endif
            </div>
        </div>

        <div class="phone">
            <div class="phone-in">
                <div class="tag">{{ $templates[$template] ?? 'Letter' }}</div>
                <h2 class="{{ $title === '' ? 'muted' : '' }}">{{ $title !== '' ? $title : 'Your title' }}</h2>
                @if ($recipient !== '') <p>Dear {{ $recipient }},</p> @endif
                @if ($love !== '') <p>{{ $love }}</p> @endif
                @if ($story !== '') <p>{{ $story }}</p> @endif
                @if ($dreams !== '') <p>{{ $dreams }}</p> @endif
                @if ($love === '' && $story === '' && $dreams === '') <p class="muted">Your words will appear here as you write.</p> @endif
                @if ($sender !== '') <p>— {{ $sender }}</p> @endif
            </div>
        </div>
    </div>
</div>
