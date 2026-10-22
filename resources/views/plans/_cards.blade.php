<style>
.pl-head{text-align:center;margin-bottom:26px}
main.wrap{max-width:none}
.pl-head,.pl-grid,.pl-exp{max-width:1000px;margin-left:auto;margin-right:auto}
.pl-head{margin-bottom:26px}
.pl-toggle{display:inline-flex;padding:4px;border-radius:999px;border:1px solid rgba(34,211,238,.25);background:rgba(10,10,32,.65);margin-top:14px}
.pl-toggle button{border:0;background:none;color:#94a3b8;padding:8px 20px;border-radius:999px;font:inherit;font-size:14px;cursor:pointer;transition:.15s}
.pl-toggle button.on{background:#22d3ee;color:#04111f;font-weight:600}
.pl-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,300px));gap:20px;justify-content:center;margin-left:auto;margin-right:auto}
.pl-card{position:relative;display:flex;flex-direction:column;padding:26px;border-radius:18px;background:rgba(10,10,32,.65);
    backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);border:1px solid rgba(34,211,238,.2);transition:.2s}
.pl-card:hover{border-color:#22d3ee;box-shadow:0 0 28px rgba(34,211,238,.18)}
.pl-card.cur{border-color:#22d3ee;box-shadow:0 0 34px rgba(34,211,238,.25)}
.pl-badge{position:absolute;top:-12px;right:18px;padding:4px 12px;border-radius:999px;font-size:11px;font-weight:700;
    background:#22d3ee;color:#04111f;letter-spacing:.04em}
.pl-card h3{margin:0 0 4px;font-size:20px;color:#fff}
.pl-price{margin:14px 0 2px;font-size:36px;font-weight:800;color:#fff;line-height:1}
.pl-price small{font-size:14px;font-weight:500;color:#94a3b8}
.pl-note{min-height:20px;margin:0 0 18px;font-size:13px;color:#94a3b8}
.pl-save{color:#34d399;font-weight:600}
.pl-list{list-style:none;margin:0 0 22px;padding:0;flex:1}
.pl-list li{display:flex;gap:10px;align-items:flex-start;padding:7px 0;font-size:14px;color:#cbd5e1}
.pl-list .bi{margin-top:2px;color:#22d3ee}
.pl-list li.no{color:#64748b}
.pl-list li.no .bi{color:#64748b}
.pl-cta{display:block;width:100%;padding:11px 16px;border-radius:999px;border:1px solid rgba(148,163,184,.3);
    background:none;color:#e5e7eb;font:inherit;font-size:14px;text-align:center}
.pl-cta[disabled]{opacity:.6;cursor:not-allowed}
.pl-cta.cur{border-color:#22d3ee;color:#22d3ee}
.pl-exp{margin:20px 0 0;text-align:center;color:#94a3b8;font-size:13px}
</style>

@php $plan_label = fn ($d) => $d >= 365 ? (round($d / 365) <= 1 ? '1 año' : round($d / 365) . ' años') : $d . ' días'; @endphp

<div class="pl-head">
    <h1 class="title">Planes</h1>
    <p class="sub" style="margin-bottom:0">Elige cuánto quieres guardar y compartir en tus cartas.</p>
    <div class="pl-toggle" id="plToggle">
        <button type="button" data-cycle="monthly" class="on">Mensual</button>
        <button type="button" data-cycle="yearly">Anual</button>
    </div>
</div>


<div class="pl-grid">
    @foreach ($plans as $p)
        @php
            $isCur = ($mode ?? 'auth') === 'auth' && isset($current) && $current->code === $p->code;
            $free = (float) $p->price_monthly <= 0;
            $hasYear = ! $free && $p->price_yearly !== null;
            $yearly = $p->priceFor('yearly');
            $saving = ($hasYear && $p->price_monthly > 0) ? round((1 - $yearly / ($p->price_monthly * 12)) * 100) : 0;
            $fmt = fn ($n) => number_format($n, fmod((float) $n, 1) ? 2 : 0, ',', '.');
        @endphp
        <div class="pl-card {{ $isCur ? 'cur' : '' }}">
            @if ($isCur)<span class="pl-badge">TU PLAN ACTUAL</span>@endif
            <h3>{{ $p->name }}</h3>

            @if ($free)
                <div class="pl-price">Gratis</div>
                <p class="pl-note">Para empezar, sin costo.</p>
            @else
                <div class="pl-price" data-monthly="{{ $fmt($p->price_monthly) }}" data-yearly="{{ $fmt($yearly) }}">
                    <span class="pl-amt">{{ $p->currency === 'USD' ? 'US$' : '$' }}{{ $fmt($p->price_monthly) }}</span>
                    <small class="pl-per">/ mes</small>
                </div>
                <p class="pl-note">
                    <span class="pl-m">Facturado cada mes</span>
                    <span class="pl-y" hidden>
                        {{ $p->currency === 'USD' ? 'US$' : '$' }}{{ $fmt($yearly / 12) }} / mes, facturado al año
                        @if ($saving > 0)<span class="pl-save">· ahorras {{ $saving }}%</span>@endif
                    </span>
                </p>
            @endif

            <ul class="pl-list">
                <li><i class="bi bi-images"></i> Hasta {{ $p->max_photos }} fotos por carta</li>
                <li><i class="bi bi-hourglass-split"></i> Disponible hasta {{ $plan_label($p->max_days) }}</li>
                <li class="{{ $p->allow_music ? '' : 'no' }}"><i class="bi {{ $p->allow_music ? 'bi-check-lg' : 'bi-x-lg' }}"></i> Banda sonora (YouTube, Spotify…)</li>
                <li class="{{ $p->premium_templates ? '' : 'no' }}"><i class="bi {{ $p->premium_templates ? 'bi-check-lg' : 'bi-x-lg' }}"></i> Plantillas premium (Starry night, Pixel city)</li>
            </ul>

            @if ($isCur)
                <span class="pl-cta cur">Plan actual</span>
            @elseif (($mode ?? 'auth') === 'public')
                <a class="pl-cta" href="{{ route('register') }}" style="{{ $free ? '' : 'background:#22d3ee;border-color:#22d3ee;color:#04111f;font-weight:600;' }}text-decoration:none">{{ $free ? 'Empezar gratis' : 'Crear cuenta y contratar' }}</a>
            @elseif ($free)
                <span class="pl-cta" style="opacity:.6">Incluido</span>
            @else
                <form method="POST" action="{{ route('billing.checkout') }}">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $p->id }}">
                    <input type="hidden" name="cycle" value="monthly">
                    <select name="provider" style="width:100%;margin-bottom:10px;padding:9px 12px;border-radius:10px;background:rgba(15,15,45,.8);color:#fff;border:1px solid rgba(148,163,184,.3);font:inherit;font-size:13px">
                        <option value="mercadopago">MercadoPago (se cobra en {{ config('billing.mp_currency') }})</option>
                        <option value="stripe" disabled>Tarjeta en USD (pronto)</option>
                    </select>
                    <button type="submit" class="pl-cta" style="background:#22d3ee;border-color:#22d3ee;color:#04111f;font-weight:600;cursor:pointer">Contratar</button>
                    <button type="submit" formaction="{{ route('billing.qr') }}" class="pl-cta" style="margin-top:8px;background:transparent;border-color:#22d3ee;color:#22d3ee;font-weight:600;cursor:pointer">Pagar con QR</button>
                </form>
            @endif
        </div>
    @endforeach
</div>


<script>
(function () {
    var box = document.getElementById('plToggle');
    box.addEventListener('click', function (e) {
        var b = e.target.closest('button'); if (!b) return;
        var y = b.dataset.cycle === 'yearly';
        document.querySelectorAll('input[name=cycle]').forEach(function (i) { i.value = b.dataset.cycle; });
        box.querySelectorAll('button').forEach(function (x) { x.classList.toggle('on', x === b); });
        document.querySelectorAll('.pl-price[data-monthly]').forEach(function (el) {
            var cur = el.querySelector('.pl-amt').textContent.replace(/[0-9.,\s]/g, '');
            el.querySelector('.pl-amt').textContent = cur + (y ? el.dataset.yearly : el.dataset.monthly);
            el.querySelector('.pl-per').textContent = y ? '/ año' : '/ mes';
        });
        document.querySelectorAll('.pl-m').forEach(function (el) { el.hidden = y; });
        document.querySelectorAll('.pl-y').forEach(function (el) { el.hidden = !y; });
    });
})();
</script>
@if (($mode ?? 'auth') === 'public')
<p class="pl-exp">Los precios se muestran en dólares y se cobran en pesos argentinos al cambio del día.</p>
@endif
