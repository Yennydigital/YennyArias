<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pagar con QR</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<style>
body{font-family:system-ui,sans-serif;background:#f5f5f7;display:flex;justify-content:center;padding:32px 16px}
.card{background:#fff;border-radius:16px;padding:28px;max-width:380px;width:100%;text-align:center;box-shadow:0 4px 24px rgba(0,0,0,.08)}
#qr{display:inline-block;margin:16px 0}
.muted{color:#666;font-size:14px}
</style>
</head>
<body>
<div class="card">
  <h2>Umbra {{ $plan->name }}</h2>
  <p class="muted">{{ $payment->billing_cycle === 'yearly' ? 'Anual' : 'Mensual' }}</p>
  <h1>$ {{ number_format((float) $payment->amount, 0, ',', '.') }}</h1>
  <div id="qr"></div>
  <p class="muted">Escaneá el código con Mercado Pago o la app de tu banco o billetera.</p>
  <p id="msg" class="muted">Esperando el pago... <span id="left"></span></p>
  <p><a href="{{ route('plans.index') }}">Volver a planes</a></p>
</div>
<script>
new QRCode(document.getElementById('qr'), {text: @json($qrData), width: 240, height: 240});
var end = Date.now() + 15 * 60 * 1000;
var url = @json(route('billing.qr.status', $payment->external_reference));
var done = false;
function tick() {
  if (done) return;
  var s = Math.max(0, Math.round((end - Date.now()) / 1000));
  document.getElementById('left').textContent = Math.floor(s / 60) + ':' + String(s % 60).padStart(2, '0');
  if (s === 0) { done = true; document.getElementById('msg').textContent = 'El QR venció. Volvé a planes y generá uno nuevo.'; }
}
function poll() {
  if (done) return;
  fetch(url, {headers: {'Accept': 'application/json'}}).then(function (r) { return r.json(); }).then(function (d) {
    if (d.status === 'approved') { done = true; window.location = @json(route('plans.index')); }
    else if (d.status !== 'pending') { done = true; document.getElementById('msg').textContent = 'El pago no se completó.'; }
  }).catch(function () {});
}
setInterval(tick, 1000); setInterval(poll, 4000); tick();
</script>
</body>
</html>
