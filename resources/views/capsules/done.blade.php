<x-umbra-capsule-layout title="Cápsula sellada" :noindex="true">
    <section class="card center">
        @include('capsules._orb')
        <h1>Tu cápsula está sellada</h1>
        <p class="lead">Este es el código para abrirla. Guárdalo ahora: no queda guardado en ningún lado y no se puede recuperar.</p>

        <div class="code">{{ $done['code'] }}</div>

        <div class="actions">
            <button class="btn" type="button" id="copyLink">Copiar link con código</button>
            <button class="btn ghost" type="button" id="copyCode">Copiar solo el código</button>
            <a class="btn ghost" id="wa" target="_blank" rel="noopener">Enviar por WhatsApp</a>
        </div>

        <div class="qrbox"><div id="qr"></div></div>
        <p class="hint" style="text-align:center">El QR abre el link con el código ya puesto.</p>

        <ul class="facts">
            @if ($done['opens_at'])
                <li>Se podrá abrir desde <time data-iso="{{ $done['opens_at'] }}">{{ $done['opens_at'] }}</time></li>
            @else
                <li>Se puede abrir desde ya.</li>
            @endif
            <li>Disponible hasta <time data-iso="{{ $done['expires_at'] }}">{{ $done['expires_at'] }}</time></li>
            @if ($done['burn'])
                <li>Se destruye después de leerse una vez.</li>
            @endif
        </ul>

        <a class="link" href="{{ route('capsulas.crear') }}">Escribir otra cápsula</a>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        (function () {
            var code = @json($done['code']);
            var link = location.origin + @json(route('capsulas.abrir', [], false)) + '#' + code;

            function copy(text, btn) {
                var ok = function () {
                    var t = btn.textContent;
                    btn.textContent = 'Copiado';
                    setTimeout(function () { btn.textContent = t; }, 1600);
                };
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(text).then(ok);
                } else {
                    var a = document.createElement('textarea');
                    a.value = text; document.body.appendChild(a); a.select();
                    document.execCommand('copy'); a.remove(); ok();
                }
            }

            document.getElementById('copyLink').onclick = function () { copy(link, this); };
            document.getElementById('copyCode').onclick = function () { copy(code, this); };
            document.getElementById('wa').href = 'https://wa.me/?text=' + encodeURIComponent('Dejé algo para ti en Umbra. Ábrelo aquí: ' + link);

            if (window.QRCode) {
                new QRCode(document.getElementById('qr'), {text: link, width: 168, height: 168, colorDark: '#04111f', colorLight: '#ffffff'});
            }
        })();
    </script>
</x-umbra-capsule-layout>
