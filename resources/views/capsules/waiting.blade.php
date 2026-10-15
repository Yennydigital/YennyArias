<x-umbra-capsule-layout title="Todavía sellada" :noindex="true">
    <section class="card center">
        @include('capsules._orb')
        <h1>Todavía está sellada</h1>
        <p class="lead">Se podrá abrir el <time data-iso="{{ $opensAt }}">{{ $opensAt }}</time>.</p>

        <div class="count" id="count">--</div>

        <form method="POST" action="{{ route('capsulas.abrir.enviar') }}">
            @csrf
            <input type="hidden" name="code" value="{{ $code }}">
            <button class="btn" id="go" type="submit" disabled>Abrir ahora</button>
        </form>

        <a class="link" href="{{ route('capsulas.abrir') }}">Volver</a>
    </section>

    <script>
        (function () {
            var t = new Date(@json($opensAt)).getTime();
            var el = document.getElementById('count'), go = document.getElementById('go');
            function p(n) { return String(n).padStart(2, '0'); }
            function tick() {
                var d = t - Date.now();
                if (d <= 0) { el.textContent = 'Ya puedes abrirla'; go.disabled = false; return; }
                var s = Math.floor(d / 1000);
                var D = Math.floor(s / 86400), H = Math.floor(s % 86400 / 3600), M = Math.floor(s % 3600 / 60), S = s % 60;
                el.textContent = (D > 0 ? D + 'd ' : '') + p(H) + ':' + p(M) + ':' + p(S);
                setTimeout(tick, 1000);
            }
            tick();
        })();
    </script>
</x-umbra-capsule-layout>
