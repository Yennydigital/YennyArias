<x-umbra-capsule-layout title="Abrir una cápsula">
    <section class="card center">
        @include('capsules._orb')
        <h1>Abrir una cápsula</h1>
        <p class="lead">Escribe el código que te dieron.</p>

        @if ($errors->any())
            <div class="err">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('capsulas.abrir.enviar') }}">
            @csrf
            <input id="codeInput" name="code" class="codein" autocomplete="off" autocapitalize="characters" spellcheck="false"
                   placeholder="XXXX-XXXX-XXXX" value="{{ old('code') }}" required>
            <button class="btn" type="submit">Abrir</button>
        </form>

        <a class="link" href="{{ route('capsulas.crear') }}">O escribe una cápsula tuya</a>
    </section>

    <script>
        (function () {
            var i = document.getElementById('codeInput');
            function fmt(v) {
                var s = v.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 12);
                return (s.match(/.{1,4}/g) || []).join('-');
            }
            i.addEventListener('input', function () { i.value = fmt(i.value); });
            if (location.hash.length > 1) {
                i.value = fmt(decodeURIComponent(location.hash.slice(1)));
                history.replaceState(null, '', location.pathname);
            } else if (i.value) {
                i.value = fmt(i.value);
            }
        })();
    </script>
</x-umbra-capsule-layout>
