@php
    $xPhotos = is_array($payload['photos'] ?? null) ? $payload['photos'] : [];
    $xMusic = \App\Support\MusicLink::clean($payload['music'] ?? null);
    $xMeta = $xMusic ? \App\Support\MusicMeta::fetch($xMusic) : null;
@endphp

@if (count($xPhotos))
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;margin:20px 0">
        @foreach ($xPhotos as $src)
            @if (is_string($src) && str_starts_with($src, 'data:image/jpeg;base64,'))
                <figure style="margin:0">
                    <img src="{{ $src }}" alt="" loading="lazy" style="width:100%;border-radius:12px;display:block">
                    <a href="{{ $src }}" download="foto-{{ $loop->iteration }}.jpg"
                       style="display:block;margin-top:6px;padding:6px 0;text-align:center;font-size:12px;border-radius:8px;border:1px solid rgba(255,255,255,.2);color:inherit;text-decoration:none;opacity:.85">
                        &#8595; Descargar
                    </a>
                </figure>
            @endif
        @endforeach
    </div>
@endif

@if ($xMusic)
    @if ($xMeta)
        <a href="{{ $xMusic }}" target="_blank" rel="noopener noreferrer"
           style="display:flex;gap:14px;align-items:center;margin:16px 0;padding:10px;border-radius:14px;border:1px solid rgba(255,255,255,.22);background:rgba(255,255,255,.04);color:inherit;text-decoration:none">
            @if ($xMeta['thumb'])
                <img src="{{ $xMeta['thumb'] }}" alt="" style="width:64px;height:64px;border-radius:10px;object-fit:cover;flex:none">
            @else
                <div style="width:64px;height:64px;border-radius:10px;flex:none;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.08);font-size:24px">&#9834;</div>
            @endif
            <div style="min-width:0;flex:1">
                <div style="font-size:11px;letter-spacing:.1em;text-transform:uppercase;opacity:.55">&#9834; Banda sonora &middot; {{ $xMeta['provider'] }}</div>
                <div style="font-weight:600;margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $xMeta['title'] }}</div>
                @if ($xMeta['artist'])
                    <div style="font-size:13px;opacity:.65;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $xMeta['artist'] }}</div>
                @endif
            </div>
            <div style="flex:none;font-size:12px;font-weight:600;opacity:.85;padding-right:4px">Escuchar &#8599;</div>
        </a>
    @else
        <p style="margin:16px 0">
            <a href="{{ $xMusic }}" target="_blank" rel="noopener noreferrer"
               style="display:inline-block;padding:10px 18px;border-radius:999px;border:1px solid rgba(255,255,255,.25);color:inherit;text-decoration:none">
                &#9834; Escuchar la banda sonora
            </a>
        </p>
    @endif
@endif
