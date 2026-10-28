<div x-data x-init="$wire.tz = Intl.DateTimeFormat().resolvedOptions().timeZone">
    @include('capsules._fontfaces')
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
        .wz-drop{position:relative;border:2px dashed rgba(255,255,255,.25);border-radius:14px;padding:22px;text-align:center;font-size:13px;opacity:.9}
        .wz-drop input{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%}
        .wz-thumbs{display:grid;grid-template-columns:repeat(auto-fill,minmax(84px,1fr));gap:8px;margin-top:10px}
        .wz-thumb{position:relative}
        .wz-thumb img{width:100%;aspect-ratio:1;object-fit:cover;border-radius:10px;display:block}
        .wz-thumb button{position:absolute;top:4px;right:4px;width:22px;height:22px;border-radius:50%;border:0;background:rgba(0,0,0,.7);color:#fff;cursor:pointer;line-height:1}
        .phone-photos{display:grid;grid-template-columns:repeat(2,1fr);gap:6px;margin:0 0 14px}
        .phone-photos img{width:100%;border-radius:8px;display:block}
        .mp-wrap{position:relative}
        .mp-badge{position:absolute;right:10px;top:50%;transform:translateY(-50%);padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600;color:#fff;pointer-events:none}
        .mp-carousel{position:relative;margin-top:14px}
        .mp-track{display:flex;gap:12px;overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none;padding:4px}
        .mp-track::-webkit-scrollbar{display:none}
        .mp-card{flex:0 0 170px;scroll-snap-align:start;border:1px solid rgba(255,255,255,.15);border-radius:12px;background:rgba(255,255,255,.04);overflow:hidden;transition:.2s}
        .mp-card.on{border-color:#a78bfa;box-shadow:0 0 0 2px rgba(167,139,250,.3)}
        .mp-top{display:flex;gap:10px;align-items:center;padding:12px}
        .mp-ico{width:40px;height:40px;flex:none;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#f1f1f4}
        .mp-ico svg{width:26px;height:26px;display:block}
        .mp-name{font-size:13px;font-weight:600}
        .mp-host{font-size:11px;opacity:.55;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:90px}
        .mp-open{display:block;text-align:center;padding:8px;border-top:1px solid rgba(255,255,255,.12);font-size:12px;font-weight:600;color:inherit;text-decoration:none;opacity:.8}
        .mp-open:hover{background:rgba(255,255,255,.06);opacity:1}
        .mp-arrow{position:absolute;top:50%;transform:translateY(-50%);width:34px;height:34px;border-radius:50%;border:1px solid rgba(255,255,255,.2);background:#171722;color:inherit;cursor:pointer;z-index:2;padding:0;line-height:1}
        .mp-arrow.l{left:-12px}
        .mp-arrow.r{right:-12px}
        .mp-dots{display:flex;justify-content:center;gap:6px;margin-top:12px}
        .mp-dots i{height:6px;width:6px;border-radius:999px;background:rgba(255,255,255,.2);transition:.2s}
        .mp-dots i.on{width:20px;background:#f472b6}
        /* ===== PREVIEW: STARRY NIGHT ===== */
        .phone.pv-starry-night{border-color:#1b1250;box-shadow:0 0 40px rgba(129,140,248,.35),0 20px 50px rgba(0,0,0,.5)}
        .pv-starry-night .phone-in{background:radial-gradient(ellipse at 50% 0%,#1b1250 0%,#0a0a2a 55%,#03030f 100%);font-family:Georgia,'Times New Roman',serif;color:#eef2ff;text-shadow:0 0 14px rgba(165,180,252,.3)}
        .pv-starry-night .phone-in::before{content:"\263E";display:block;text-align:center;font-size:30px;color:#fde68a;text-shadow:0 0 20px rgba(253,230,138,.8);margin-bottom:6px}
        .pv-starry-night h2{color:#c4b5fd}
        .pv-starry-night .phone-photos img{border-radius:12px;box-shadow:0 0 18px rgba(129,140,248,.45)}
        /* ===== PREVIEW: PIXEL CITY ===== */
        .phone.pv-pixel-city{border-color:#f0abfc;border-radius:12px;box-shadow:6px 6px 0 #22d3ee}
        .pv-pixel-city .phone-in{background:repeating-linear-gradient(0deg,rgba(0,0,0,.18) 0,rgba(0,0,0,.18) 1px,transparent 1px,transparent 3px),linear-gradient(rgba(20,0,40,.62),rgba(2,2,20,.82)),url('{{ asset('img/umbra-bg.gif') }}') center/cover no-repeat;font-family:ui-monospace,Menlo,Consolas,monospace;color:#e0f2fe;text-shadow:0 0 8px rgba(34,211,238,.45)}
        .pv-pixel-city .phone-in::before{content:"> umbra.exe --open letter";display:block;margin-bottom:12px;padding-bottom:8px;border-bottom:1px dashed rgba(34,211,238,.5);color:#22d3ee;font-size:11px;letter-spacing:.08em}
        .pv-pixel-city h2{color:#f0abfc;text-transform:uppercase;letter-spacing:.08em;font-size:18px}
        .pv-pixel-city .tag{color:#22d3ee;opacity:1}
        .pv-pixel-city .phone-photos img{image-rendering:pixelated;border:2px solid #22d3ee;border-radius:0;box-shadow:3px 3px 0 #f0abfc}
        /* ===== PREVIEW: LOVE LETTER ===== */
        .phone.pv-love-letter{border-color:#fb7185;box-shadow:0 0 40px rgba(251,113,133,.35),0 20px 50px rgba(0,0,0,.5)}
        .pv-love-letter .phone-in{background:linear-gradient(rgba(40,8,28,.95),rgba(15,2,15,.98));font-family:Georgia,'Times New Roman',serif;color:#ffe4e6}
        .pv-love-letter .phone-in::before{content:"\2665";display:block;text-align:center;font-size:30px;color:#fb7185;text-shadow:0 0 20px rgba(251,113,133,.8);margin-bottom:6px;animation:pvfloat 3s ease-in-out infinite}
        .pv-love-letter h2{color:#fda4af;font-style:italic}
        .pv-love-letter .phone-photos img{border-radius:12px;box-shadow:0 0 18px rgba(251,113,133,.45)}
        @keyframes pvfloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-5px)}}
        /* ===== PREVIEW: BIRTHDAY ===== */
        .phone.pv-birthday{border-color:#facc15;box-shadow:0 0 40px rgba(250,204,21,.3),0 20px 50px rgba(0,0,0,.5)}
        .pv-birthday .phone-in{background:radial-gradient(circle at 15% 12%,rgba(250,204,21,.9) 0 2px,transparent 3px),radial-gradient(circle at 85% 20%,rgba(244,114,182,.9) 0 2px,transparent 3px),radial-gradient(circle at 70% 60%,rgba(34,211,238,.9) 0 2px,transparent 3px),radial-gradient(circle at 20% 85%,rgba(163,230,53,.9) 0 2px,transparent 3px),linear-gradient(160deg,#2a0a4a,#0a0a2a);color:#fff}
        .pv-birthday .phone-in::before{content:"\1F382  \00A1Feliz cumplea\00F1os!  \1F389";display:block;text-align:center;font-size:15px;font-weight:800;color:#facc15;margin-bottom:10px}
        .pv-birthday h2{color:#f472b6;font-weight:800}
        .pv-birthday .tag{color:#facc15;opacity:1}
        .pv-birthday .phone-photos img{border:2px solid #facc15;border-radius:10px}
        /* ===== PREVIEW: THANK YOU ===== */
        .phone.pv-thank-you{border-color:#34d399;box-shadow:0 0 40px rgba(52,211,153,.25),0 20px 50px rgba(0,0,0,.5)}
        .pv-thank-you .phone-in{background:linear-gradient(rgba(6,30,28,.97),rgba(2,10,12,.99));border-top:4px solid #fcd34d;color:#ecfdf5}
        .pv-thank-you .phone-in::before{content:"Gracias";display:block;text-align:center;font-family:Georgia,serif;font-size:24px;color:#fcd34d;letter-spacing:.12em;margin-bottom:8px}
        .pv-thank-you h2{color:#6ee7b7}
        .pv-thank-you .tag{color:#fcd34d;opacity:1}
        .pv-thank-you .phone-photos img{border-radius:10px;box-shadow:0 0 18px rgba(52,211,153,.4)}

        /* pv-fonts: fuente elegida en la vista previa */
@foreach (\App\Support\LetterFonts::all() as $fk => $fv)
@if ($fk !== 'default')
        .phone[data-font="{{ $fk }}"] .phone-in,.phone[data-font="{{ $fk }}"] .phone-in *{font-family:{!! $fv['stack'] !!}!important}
@endif
@endforeach
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
                                class="wz-tpl {{ $template === $key ? 'on' : '' }}">{{ $label }}@if (\App\Support\Templates::isPremium($key))<small style="display:block;opacity:.75;margin-top:4px">★ Premium @unless (\App\Support\Templates::canUse($key, auth()->user())) · 🔒@endunless</small>@endif</button>
                    @endforeach
                </div>
                @error('template') <div class="wz-err">{{ $message }}</div> @enderror
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

                @include('livewire.letters._stickers')

                <label>What I love about you</label>
                <textarea rows="4" wire:model.live.debounce.250ms="love"></textarea>

                <label>Our story</label>
                <textarea rows="4" wire:model.live.debounce.250ms="story"></textarea>

                <label>Our dreams</label>
                <textarea rows="4" wire:model.live.debounce.250ms="dreams"></textarea>
                @error('love') <div class="wz-err">{{ $message }}</div> @enderror
                @error('story') <div class="wz-err">{{ $message }}</div> @enderror
                @error('dreams') <div class="wz-err">{{ $message }}</div> @enderror

                <div class="wz-field" style="margin-bottom:14px">
                    <label>Custom background (optional)</label>
                    <input type="file" wire:model="bgFile" accept="image/jpeg,image/png,image/webp">
                    <div wire:loading wire:target="bgFile" class="wz-err" style="color:inherit;opacity:.7">Uploading…</div>
                    @error('bgFile') <div class="wz-err">{{ $message }}</div> @enderror
                    @if ($bgFile)
                        <div style="display:flex;gap:10px;align-items:center;margin-top:8px">
                            <img src="{{ $bgFile->temporaryUrl() }}" alt="" style="width:120px;height:70px;object-fit:cover;border-radius:8px">
                            <button type="button" wire:click="removeBg">Remove</button>
                        </div>
                    @endif
                </div>
                <div class="wz-field" style="margin-bottom:14px">
                    <label>Font</label>
                    <select wire:model.live="font">
                        @foreach (\App\Support\LetterFonts::all() as $fk => $fv)
                            <option value="{{ $fk }}">{{ $fv['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <label>Photos (up to {{ $plan->max_photos }})</label>
                <div class="wz-drop">
                    Click or drag your photos here
                    <input type="file" wire:model="newPhotos" multiple accept="image/jpeg,image/png,image/webp">
                </div>
                <div wire:loading wire:target="newPhotos" class="wz-err" style="color:inherit;opacity:.7">Uploading…</div>
                @error('newPhotos') <div class="wz-err">{{ $message }}</div> @enderror
                @if (count($photos))
                    <div class="wz-thumbs">
                        @foreach ($photos as $i => $photo)
                            <div class="wz-thumb" wire:key="photo-{{ $i }}-{{ $photo->getFilename() }}">
                                <img src="{{ $photo->temporaryUrl() }}" alt="">
                                <button type="button" wire:click="removePhoto({{ $i }})" aria-label="Remove photo">×</button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div
                    x-data="{
                        val: $wire.music || '',
                        page: 0,
                        providers: [
                            { name: 'YouTube',      host: 'youtube.com',      url: 'https://www.youtube.com',  match: /youtube\.com|youtu\.be/i,   color: '#ff0000' },
                            { name: 'Spotify',      host: 'open.spotify.com', url: 'https://open.spotify.com', match: /spotify\.com/i,             color: '#1db954' },
                            { name: 'Apple Music',  host: 'music.apple.com',  url: 'https://music.apple.com',  match: /music\.apple\.com/i,        color: '#fa2d48' },
                            { name: 'Deezer',       host: 'deezer.com',       url: 'https://www.deezer.com',   match: /deezer\.(com|page\.link)/i, color: '#a238ff' },
                            { name: 'Amazon Music', host: 'music.amazon.com', url: 'https://music.amazon.com', match: /music\.amazon\./i,          color: '#25a7da' },
                        ],
                        get active() { return this.providers.find(p => p.match.test(this.val)) },
                        scrollBy(d) { const t = this.$refs.track; t.scrollBy({ left: d * t.clientWidth * 0.8, behavior: 'smooth' }) },
                        onScroll() { const t = this.$refs.track; const max = t.scrollWidth - t.clientWidth; this.page = max <= 0 ? 0 : Math.round(t.scrollLeft / max) }
                    }"
                >
                    <label>Soundtrack (YouTube, Spotify, Apple Music, Deezer or Amazon Music link)</label>

                    <div class="mp-wrap">
                        <input type="url" wire:model.live.debounce.400ms="music" maxlength="500" @disabled(! $plan->allow_music)
                               @input="val = $event.target.value"
                               placeholder="Pega un enlace de YouTube, Spotify, Apple Music, Deezer o Amazon Music"
                               :style="active ? 'padding-right:110px' : ''">
                        <template x-if="active">
                            <span class="mp-badge" :style="`background:${active.color}`" x-text="active.name"></span>
                        </template>
                    </div>
                    @error('music') <div class="wz-err">{{ $message }}</div> @enderror
                    @unless ($plan->allow_music)
                        <div class="wz-err" style="color:inherit;opacity:.85">
                            🔒 La música está disponible en Plus.
                            <a href="{{ \Illuminate\Support\Facades\Route::has('plans.index') ? route('plans.index') : url('/plans') }}">Ver planes</a>
                        </div>
                    @endunless

                    <div wire:ignore class="mp-carousel">
                        <button type="button" class="mp-arrow l" @click="scrollBy(-1)" aria-label="Anterior">‹</button>
                        <div class="mp-track" x-ref="track" @scroll.passive="onScroll">
                            <template x-for="p in providers" :key="p.name">
                                <div class="mp-card" :class="active && active.name === p.name ? 'on' : ''">
                                    <div class="mp-top">
                                        <div class="mp-ico">
                                            <template x-if="p.name === 'YouTube'">
                                                <svg viewBox="0 0 24 24"><rect x="1.5" y="5" width="21" height="14" rx="4.5" fill="#ff0000"/><path d="M10 8.9v6.2l5.4-3.1z" fill="#fff"/></svg>
                                            </template>
                                            <template x-if="p.name === 'Spotify'">
                                                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10.5" fill="#1db954"/><path d="M6.6 9.2c3.8-1.1 8.2-.8 11.2 1M7.4 12.4c3.2-.9 6.8-.6 9.4 1M8.2 15.5c2.5-.6 5-.4 7 .8" fill="none" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/></svg>
                                            </template>
                                            <template x-if="p.name === 'Apple Music'">
                                                <svg viewBox="0 0 24 24"><rect x="1.5" y="1.5" width="21" height="21" rx="5.5" fill="#fa2d48"/><path d="M10 16.8V8.4l6.2-1.5v8.4" fill="none" stroke="#fff" stroke-width="1.6" stroke-linejoin="round"/><circle cx="8.4" cy="16.9" r="1.9" fill="#fff"/><circle cx="14.6" cy="15.4" r="1.9" fill="#fff"/></svg>
                                            </template>
                                            <template x-if="p.name === 'Deezer'">
                                                <svg viewBox="0 0 24 24"><rect x="1" y="17" width="4" height="3" fill="#a238ff"/><rect x="6" y="14" width="4" height="6" fill="#ff9900"/><rect x="11" y="11" width="4" height="9" fill="#1db954"/><rect x="16" y="8" width="4" height="12" fill="#25a7da"/><rect x="1" y="12.5" width="4" height="3" fill="#ff0092"/><rect x="21" y="5" width="2.5" height="15" fill="#fa2d48"/></svg>
                                            </template>
                                            <template x-if="p.name === 'Amazon Music'">
                                                <svg viewBox="0 0 24 24"><rect x="1.5" y="1.5" width="21" height="21" rx="5.5" fill="#25a7da"/><path d="M15.2 10.6c0-2-1.2-3.1-3.2-3.1-1.4 0-2.5.5-3.3 1.4M15.2 10.6v5.2M15.2 13.2c-.5.6-1.5 1-2.6 1-1.4 0-2.2-.7-2.2-1.7s.9-1.7 2.4-1.7c.9 0 1.9.1 2.4.4" fill="none" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/><path d="M6.5 17.5c3.2 1.7 7.8 1.8 11 0" fill="none" stroke="#fff" stroke-width="1.4" stroke-linecap="round"/></svg>
                                            </template>
                                        </div>
                                        <div>
                                            <div class="mp-name" x-text="p.name"></div>
                                            <div class="mp-host" x-text="p.host"></div>
                                        </div>
                                    </div>
                                    <a class="mp-open" :href="p.url" target="_blank" rel="noopener noreferrer">Abrir ↗</a>
                                </div>
                            </template>
                        </div>
                        <button type="button" class="mp-arrow r" @click="scrollBy(1)" aria-label="Siguiente">›</button>
                    </div>

                    <div class="mp-dots"><i :class="page === 0 ? 'on' : ''"></i><i :class="page === 1 ? 'on' : ''"></i></div>
                </div>
            @else
                <h3>Seal your letter</h3>
                <p style="opacity:.7;font-size:13px">You will get a 12-character code. It is not stored anywhere, so keep it safe: without it nobody can open the letter.</p>

                <label>Opens on (optional)</label>
                <input type="datetime-local" wire:model="opens_at">
                @error('opens_at') <div class="wz-err">{{ $message }}</div> @enderror

                <label>Keep it available for</label>
                <select wire:model="keep">
                    @foreach ([7, 30, 90, 365] as $d)
                        <option value="{{ $d }}" @disabled($d > $plan->max_days)>{{ $d }} days{{ $d > $plan->max_days ? ' (Plus)' : '' }}</option>
                    @endforeach
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

        <div class="phone pv-{{ $template }}" data-font="{{ \App\Support\LetterFonts::clean($font) }}">
            <div class="phone-in">
                <div class="tag">{{ $templates[$template] ?? 'Letter' }}</div>
                <h2 class="{{ $title === '' ? 'muted' : '' }}">{{ $title !== '' ? $title : 'Your title' }}</h2>
                @if ($recipient !== '') <p>Dear {{ $recipient }},</p> @endif
                @if ($love !== '') <p>{{ \App\Support\Stickers::render($love) }}</p> @endif
                @if ($story !== '') <p>{{ \App\Support\Stickers::render($story) }}</p> @endif
                @if ($dreams !== '') <p>{{ \App\Support\Stickers::render($dreams) }}</p> @endif
                @if (count($photos))
                    <div class="phone-photos">
                        @foreach ($photos as $photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="">
                        @endforeach
                    </div>
                @endif
                @if (trim($music) !== '' && \App\Support\MusicLink::clean($music))
                    <p class="tag">♪ Soundtrack attached</p>
                @endif
                @if ($love === '' && $story === '' && $dreams === '') <p class="muted">Your words will appear here as you write.</p> @endif
                @if ($sender !== '') <p>— {{ $sender }}</p> @endif
            </div>
        </div>
    </div>
</div>
