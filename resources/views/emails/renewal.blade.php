<div style="font-family:system-ui,sans-serif;max-width:480px;margin:0 auto;padding:24px;color:#1a1a2e">
  <h2 style="margin:0 0 12px">Hola {{ $user->name }}</h2>
  <p>Tu plan <strong>{{ $sub->plan->name }}</strong> de Umbra vence el <strong>{{ $sub->current_period_end->format('d/m/Y') }}</strong>.</p>
  <p>Si lo renovás antes de esa fecha, el nuevo período se suma al que ya tenés, sin perder días.</p>
  <p style="margin:24px 0">
    <a href="{{ url('/plans') }}" style="background:#22d3ee;color:#04111f;padding:12px 20px;border-radius:10px;text-decoration:none;font-weight:600">Renovar mi plan</a>
  </p>
  <p style="color:#666;font-size:13px">Si no renovás, tu cuenta vuelve al plan Free. Tus cartas no se borran.</p>
</div>
