<x-umbra-capsule-layout title="Escribir una cápsula">
    <section class="card">
        <h1>Deja algo para alguien</h1>
        <p class="lead">Escribe un mensaje, séllalo y compártelo. Solo quien tenga el código podrá abrirlo.</p>

        @if ($errors->any())
            <div class="err">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('capsulas.guardar') }}">
            @csrf
            <input type="hidden" name="tz" id="tz">

            <div class="row2">
                <div>
                    <label for="to">Para (opcional)</label>
                    <input id="to" name="to" maxlength="60" value="{{ old('to') }}" placeholder="Su nombre">
                </div>
                <div>
                    <label for="from">De (opcional)</label>
                    <input id="from" name="from" maxlength="60" value="{{ old('from') }}" placeholder="Tu nombre">
                </div>
            </div>

            <label for="message">Mensaje</label>
            <textarea id="message" name="message" rows="7" maxlength="3000" required placeholder="Escribe aquí lo que quieres decir...">{{ old('message') }}</textarea>
            <div class="hint"><span id="count">0</span> / 3000</div>

            <label for="opens_at">Se puede abrir desde (opcional)</label>
            <input type="datetime-local" id="opens_at" name="opens_at" value="{{ old('opens_at') }}">
            <div class="hint">Si lo dejas vacío, se abre apenas tenga el código.</div>

            <label for="keep">Disponible durante</label>
            <select id="keep" name="keep">
                <option value="7" @selected(old('keep') == '7')>7 días</option>
                <option value="30" @selected(old('keep', '30') == '30')>30 días</option>
                <option value="90" @selected(old('keep') == '90')>90 días</option>
                <option value="365" @selected(old('keep') == '365')>1 año</option>
            </select>
            <div class="hint">Cuenta desde que se puede abrir. Después se borra sola.</div>

            <label class="check"><input type="checkbox" name="burn" value="1" @checked(old('burn'))> Destruir después de leerse una vez</label>

            <button class="btn" type="submit">Sellar cápsula</button>
        </form>
    </section>

    <script>
        (function () {
            document.getElementById('tz').value = Intl.DateTimeFormat().resolvedOptions().timeZone || '';
            var m = document.getElementById('message'), c = document.getElementById('count');
            function upd() { c.textContent = m.value.length; }
            m.addEventListener('input', upd); upd();
            var d = document.getElementById('opens_at'), n = new Date();
            n.setMinutes(n.getMinutes() - n.getTimezoneOffset());
            d.min = n.toISOString().slice(0, 16);
        })();
    </script>
</x-umbra-capsule-layout>
