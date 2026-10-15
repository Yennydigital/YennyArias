@php
    $xPhotos = is_array($payload['photos'] ?? null) ? $payload['photos'] : [];
    $xMusic = \App\Support\MusicLink::clean($payload['music'] ?? null);
@endphp

@if (count($xPhotos))
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px;margin:20px 0">
        @foreach ($xPhotos as $src)
            @if (is_string($src) && str_starts_with($src, 'data:image/jpeg;base64,'))
                <img src="{{ $src }}" alt="" loading="lazy" style="width:100%;border-radius:12px;display:block">
            @endif
        @endforeach
    </div>
@endif

@if ($xMusic)
    <p style="margin:16px 0">
        <a href="{{ $xMusic }}" target="_blank" rel="noopener noreferrer"
           style="display:inline-block;padding:10px 18px;border-radius:999px;border:1px solid rgba(255,255,255,.25);color:inherit;text-decoration:none">
            ♪ Listen to the soundtrack
        </a>
    </p>
@endif
