@props(['title' => 'Umbra', 'noindex' => false])
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if ($noindex)
        <meta name="robots" content="noindex,nofollow">
    @endif
    <title>{{ $title }} | Umbra</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,700,800&display=swap" rel="stylesheet">
    <style>
        :root{--cy:#22d3ee;--in:#6366f1}
        *{box-sizing:border-box}
        [hidden]{display:none!important}
        body{margin:0;min-height:100vh;font-family:'Inter',system-ui,sans-serif;color:#e5e7eb;
            background:linear-gradient(rgba(5,5,25,.82),rgba(5,5,25,.93)),url('{{ asset('img/umbra-bg.gif') }}') center/cover no-repeat fixed;
            image-rendering:pixelated}
        .stars{position:fixed;inset:0;pointer-events:none;opacity:.55;
            background-image:radial-gradient(2px 2px at 20px 30px,rgba(255,255,255,.8),transparent),
            radial-gradient(2px 2px at 120px 90px,rgba(34,211,238,.7),transparent),
            radial-gradient(1.5px 1.5px at 200px 160px,rgba(255,255,255,.6),transparent);
            background-size:240px 200px;animation:drift 70s linear infinite}
        .stage{position:relative;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:18px;padding:28px 16px}
        .brand{display:flex;align-items:center;gap:10px;font-weight:800;letter-spacing:.2em;text-transform:uppercase;color:#fff;text-decoration:none;font-size:15px}
        .card{width:100%;max-width:520px;padding:32px 28px;border-radius:20px;background:rgba(10,10,32,.74);
            backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid rgba(34,211,238,.28);
            box-shadow:0 0 50px rgba(34,211,238,.12),0 24px 60px rgba(0,0,0,.55)}
        .center{text-align:center}
        h1{margin:6px 0 8px;font-size:26px;font-weight:800;color:#fff}
        .lead{margin:0 0 22px;color:#94a3b8;font-size:15px;line-height:1.55}
        label{display:block;margin:16px 0 6px;font-size:13px;font-weight:500;color:#cbd5e1;text-align:left}
        input,textarea,select{width:100%;padding:12px 14px;border-radius:10px;font:inherit;font-size:15px;color:#fff;
            background:rgba(15,15,45,.8);border:1px solid rgba(148,163,184,.3);outline:none;transition:.15s}
        textarea{resize:vertical;min-height:140px;line-height:1.5}
        input:focus,textarea:focus,select:focus{border-color:var(--cy);box-shadow:0 0 0 3px rgba(34,211,238,.2)}
        select option{background:#0a0a20}
        .row2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .hint{margin-top:6px;font-size:12px;color:#64748b;text-align:left}
        .check{display:flex;align-items:center;gap:10px;margin-top:18px;font-size:14px}
        .check input{width:auto}
        .btn{display:inline-block;width:100%;margin-top:18px;padding:13px;border:0;border-radius:999px;cursor:pointer;
            font:inherit;font-size:15px;font-weight:700;color:#04111f;background:var(--cy);text-align:center;text-decoration:none;transition:.15s}
        .btn:hover{background:#67e8f9;box-shadow:0 0 22px rgba(34,211,238,.5)}
        .btn:disabled{opacity:.4;cursor:not-allowed;box-shadow:none}
        .btn.ghost{background:transparent;color:var(--cy);border:1px solid rgba(34,211,238,.45)}
        .btn.ghost:hover{background:rgba(34,211,238,.1)}
        .actions .btn{margin-top:10px}
        .link{display:inline-block;margin-top:18px;font-size:13px;color:var(--cy);text-decoration:none}
        .link:hover{text-decoration:underline}
        .err{margin-bottom:6px;padding:10px 12px;border-radius:10px;font-size:13px;text-align:left;
            background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.4);color:#fecaca}
        .foot{margin:0;font-size:12px;color:#64748b;text-align:center;max-width:420px}
        .orb{display:block;margin:0 auto 6px;animation:float 4s ease-in-out infinite,glow 3s ease-in-out infinite}
        .orbbtn{background:none;border:0;padding:0;cursor:pointer}
        .code{margin:6px 0 4px;padding:16px;border-radius:14px;font-family:ui-monospace,Menlo,Consolas,monospace;font-size:26px;
            font-weight:700;letter-spacing:.14em;color:var(--cy);background:rgba(34,211,238,.07);border:1px dashed rgba(34,211,238,.5);
            text-shadow:0 0 18px rgba(34,211,238,.6);word-break:break-all}
        .codein{text-align:center;font-family:ui-monospace,Menlo,Consolas,monospace;font-size:22px;letter-spacing:.16em;text-transform:uppercase}
        .qrbox{display:inline-block;margin-top:18px;padding:12px;background:#fff;border-radius:14px}
        .facts{list-style:none;margin:18px 0 0;padding:0;font-size:13px;color:#94a3b8;line-height:1.9}
        .count{margin:6px 0 4px;font-size:38px;font-weight:800;letter-spacing:.06em;color:var(--cy);font-variant-numeric:tabular-nums}
        .letter{text-align:left}
        .letter .to{margin:0 0 10px;color:var(--cy);font-weight:700}
        .letter .msg{white-space:pre-wrap;overflow-wrap:anywhere;font-size:17px;line-height:1.7;color:#f1f5f9}
        .letter .from{margin:20px 0 0;text-align:right;color:#cbd5e1;font-style:italic}
        .sep{border:0;border-top:1px solid rgba(148,163,184,.2);margin:22px 0 14px}
        .note{margin:0;font-size:12px;color:#94a3b8}
        .reveal{animation:reveal 1.1s ease both}
        @keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
        @keyframes glow{0%,100%{filter:drop-shadow(0 0 14px rgba(34,211,238,.55))}50%{filter:drop-shadow(0 0 34px rgba(99,102,241,.85))}}
        @keyframes reveal{from{opacity:0;transform:translateY(14px) scale(.98);filter:blur(8px)}to{opacity:1;transform:none;filter:none}}
        @keyframes drift{to{background-position:240px 200px}}
        @media(max-width:480px){.row2{grid-template-columns:1fr}.card{padding:26px 20px}.code{font-size:20px}}
    </style>
</head>
<body>
    <div class="stars"></div>
    <main class="stage">
        <a href="{{ url('/') }}" class="brand">
            <svg width="28" height="28" viewBox="0 0 64 64" aria-hidden="true">
                <defs><linearGradient id="brandg" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0" stop-color="#22d3ee"/><stop offset="1" stop-color="#6366f1"/></linearGradient></defs>
                <path d="M44 6a28 28 0 1 0 14 40A22 22 0 0 1 44 6z" fill="url(#brandg)"/>
            </svg>
            Umbra
        </a>
        {{ $slot }}
        <p class="foot">El mensaje se guarda cifrado y el código no se guarda en ningún lado.</p>
    </main>
    <script>
        document.querySelectorAll('[data-iso]').forEach(function (el) {
            var d = new Date(el.getAttribute('data-iso'));
            if (!isNaN(d)) el.textContent = d.toLocaleString('es-AR', {dateStyle: 'long', timeStyle: 'short'});
        });
    </script>
</body>
</html>
