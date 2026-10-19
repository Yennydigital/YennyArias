@php $tpl = in_array($payload['template'] ?? '', ['love-letter','birthday','thank-you','starry-night','pixel-city'], true) ? $payload['template'] : 'default'; @endphp
<x-umbra-capsule-layout title="Una cápsula para ti" :noindex="true">
@include('capsules._themes')
@include('capsules._themes_free')
    <section class="card center" id="seal">
        <button type="button" class="orbbtn" id="unseal" aria-label="Abrir la cápsula">
            @include('capsules._orb')
        </button>
        <h1>Alguien dejó algo para ti</h1>
        <p class="lead">Toca el sello para abrirlo.</p>
    </section>

    <section class="card letter tpl-{{ $tpl }}" id="letter" hidden>
        @if (! empty($payload['to']))
            <p class="to">Para {{ $payload['to'] }}</p>
        @endif

        @if (! empty($payload['title']))
            <h2 class="ttl">{{ $payload['title'] }}</h2>
        @endif

        <div class="msg">{{ $payload['message'] }}</div>
@include('capsules._extras', ['payload' => $payload])

        @if (! empty($payload['from']))
            <p class="from">De {{ $payload['from'] }}</p>
        @endif

        <hr class="sep">

        @if ($burn)
            <p class="note">Esta cápsula se destruyó al abrirse. Si quieres conservar el mensaje, guárdalo ahora.</p>
        @else
            <p class="note">Disponible hasta <time data-iso="{{ $expires }}">{{ $expires }}</time>.</p>
        @endif

        <a class="btn" href="{{ route('capsulas.crear') }}">Escribe tu propia cápsula</a>
    </section>

    <script>
        (function () {
            var s = document.getElementById('seal'), l = document.getElementById('letter');
            document.getElementById('unseal').onclick = function () {
                s.style.transition = 'opacity .5s, transform .5s';
                s.style.opacity = '0';
                s.style.transform = 'scale(.9)';
                setTimeout(function () { s.hidden = true; l.hidden = false; l.classList.add('reveal'); }, 500);
            };
        })();
    </script>
</x-umbra-capsule-layout>
