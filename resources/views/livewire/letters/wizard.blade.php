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
        .mp-ico{width:38px;height:38px;flex:none;border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700}
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
        .mp-wrap{position:relative}
        .mp-badge{position:absolute;right:10px;top:50%;transform:translateY(-50%);padding:3px 10px;border-radius:999px;font-size:11px;font-weight:600;color:#fff;pointer-events:none}
        .mp-carousel{position:relative;margin-top:14px}
        .mp-track{display:flex;gap:12px;overflow-x:auto;scroll-snap-type:x mandatory;scrollbar-width:none;padding:4px}
        .mp-track::-webkit-scrollbar{display:none}
        .mp-card{flex:0 0 170px;scroll-snap-align:start;border:1px solid rgba(255,255,255,.15);border-radius:12px;background:rgba(255,255,255,.04);overflow:hidden;transition:.2s}
        .mp-card.on{border-color:#a78bfa;box-shadow:0 0 0 2px rgba(167,139,250,.3)}
        .mp-top{display:flex;gap:10px;align-items:center;padding:12px}
        .mp-ico{width:38px;height:38px;flex:none;border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700}
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

                <label>Photos (up to 5)</label>
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
                            { name: 'YouTube',      host: 'youtube.com',      url: 'https://www.youtube.com',  match: /youtube\.com|youtu\.be/i,   color: '#ff0000', icon: '▶' },
                            { name: 'Spotify',      host: 'open.spotify.com', url: 'https://open.spotify.com', match: /spotify\.com/i,             color: '#1db954', icon: '≋' },
                            { name: 'Apple Music',  host: 'music.apple.com',  url: 'https://music.apple.com',  match: /music\.apple\.com/i,        color: '#fa2d48', icon: '♪' },
                            { name: 'Deezer',       host: 'deezer.com',       url: 'https://www.deezer.com',   match: /deezer\.(com|page\.link)/i, color: '#a238ff', icon: '▮' },
                            { name: 'Amazon Music', host: 'music.amazon.com', url: 'https://music.amazon.com', match: /music\.amazon\./i,          color: '#25a7da', icon: 'a' },
                        ],
                        get active() { return this.providers.find(p => p.match.test(this.val)) },
                        scrollBy(d) { const t = this.$refs.track; t.scrollBy({ left: d * t.clientWidth * 0.8, behavior: 'smooth' }) },
                        onScroll() { const t = this.$refs.track; const max = t.scrollWidth - t.clientWidth; this.page = max <= 0 ? 0 : Math.round(t.scrollLeft / max) }
                    }"
                >
                <div
                    x-data="{
                        val: $wire.music || '',
                        page: 0,
                        providers: [
                            { name: 'YouTube',      host: 'youtube.com',      url: 'https://www.youtube.com',  match: /youtube\.com|youtu\.be/i,   color: '#ff0000', icon: '▶' },
                            { name: 'Spotify',      host: 'open.spotify.com', url: 'https://open.spotify.com', match: /spotify\.com/i,             color: '#1db954', icon: '≋' },
                            { name: 'Apple Music',  host: 'music.apple.com',  url: 'https://music.apple.com',  match: /music\.apple\.com/i,        color: '#fa2d48', icon: '♪' },
                            { name: 'Deezer',       host: 'deezer.com',       url: 'https://www.deezer.com',   match: /deezer\.(com|page\.link)/i, color: '#a238ff', icon: '▮' },
                            { name: 'Amazon Music', host: 'music.amazon.com', url: 'https://music.amazon.com', match: /music\.amazon\./i,          color: '#25a7da', icon: 'a' },
                        ],
                        get active() { return this.providers.find(p => p.match.test(this.val)) },
                        scrollBy(d) { const t = this.$refs.track; t.scrollBy({ left: d * t.clientWidth * 0.8, behavior: 'smooth' }) },
                        onScroll() { const t = this.$refs.track; const max = t.scrollWidth - t.clientWidth; this.page = max <= 0 ? 0 : Math.round(t.scrollLeft / max) }
                    }"
                >
                    <label>Soundtrack (YouTube, Spotify, Apple Music, Deezer or Amazon Music link)</label>

                    <div class="mp-wrap">
                        <input type="url" wire:model.live.debounce.400ms="music" maxlength="500"
                               @input="val = $event.target.value"
                               placeholder="Pega un enlace de YouTube, Spotify, Apple Music, Deezer o Amazon Music"
                               :style="active ? 'padding-right:110px' : ''">
                        <template x-if="active">
                            <span class="mp-badge" :style="`background:${active.color}`" x-text="active.name"></span>
                        </template>
                    </div>
                    @error('music') <div class="wz-err">{{ $message }}</div> @enderror

                    <div wire:ignore class="mp-carousel">
                        <button type="button" class="mp-arrow l" @click="scrollBy(-1)" aria-label="Anterior">‹</button>
                        <div class="mp-track" x-ref="track" @scroll.passive="onScroll">
                            <template x-for="p in providers" :key="p.name">
                                <div class="mp-card" :class="active && active.name === p.name ? 'on' : ''">
                                    <div class="mp-top">
                                        <div class="mp-ico" :style="`background:${p.color}`" x-text="p.icon"></div>
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

                    <div wire:ignore class="mp-carousel">
                        <button type="button" class="mp-arrow l" @click="scrollBy(-1)" aria-label="Anterior">‹</button>
                        <div class="mp-track" x-ref="track" @scroll.passive="onScroll">
                            <template x-for="p in providers" :key="p.name">
                                <div class="mp-card" :class="active && active.name === p.name ? 'on' : ''">
                                    <div class="mp-top">
                                        <div class="mp-ico" :style="`background:${p.color}`" x-text="p.icon"></div>
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
