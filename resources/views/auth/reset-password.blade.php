<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Umbra | {{ __('Reset Password') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,700,800&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;font-family:'Inter',system-ui,sans-serif;color:#e5e7eb;
            background:linear-gradient(rgba(5,5,25,.78),rgba(5,5,25,.88)),url('{{ asset('img/umbra-bg.gif') }}') center/cover no-repeat fixed;
            image-rendering:pixelated;display:flex;align-items:center;justify-content:center;padding:24px}
        .card{width:100%;max-width:420px;padding:36px 32px;border-radius:18px;
            background:rgba(10,10,32,.72);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
            border:1px solid rgba(34,211,238,.28);box-shadow:0 0 40px rgba(34,211,238,.12),0 20px 50px rgba(0,0,0,.5)}
        .brand{display:flex;flex-direction:column;align-items:center;gap:10px;margin-bottom:26px}
        .brand h1{margin:0;font-size:30px;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:#fff}
        .brand p{margin:0;font-size:14px;color:#94a3b8;text-align:center;line-height:1.5}
        label{display:block;margin:16px 0 6px;font-size:13px;font-weight:500;color:#cbd5e1}
        input[type=email],input[type=password]{width:100%;padding:12px 14px;border-radius:10px;font-size:15px;color:#fff;
            background:rgba(15,15,45,.8);border:1px solid rgba(148,163,184,.3);outline:none;transition:.15s}
        input[type=email]:focus,input[type=password]:focus{border-color:#22d3ee;box-shadow:0 0 0 3px rgba(34,211,238,.2)}
        .foot a{color:#22d3ee;text-decoration:none}
        .foot a:hover{text-decoration:underline}
        button{width:100%;margin-top:22px;padding:13px;border:0;border-radius:999px;cursor:pointer;
            font-size:15px;font-weight:700;color:#04111f;background:#22d3ee;transition:.15s}
        button:hover{background:#67e8f9;box-shadow:0 0 22px rgba(34,211,238,.5)}
        .errors,.status{margin-bottom:8px;padding:10px 12px;border-radius:10px;font-size:13px}
        .errors{background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.4);color:#fecaca}
        .status{background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.4);color:#bbf7d0}
        .foot{margin-top:20px;text-align:center;font-size:13px;color:#94a3b8}
    </style>
</head>
<body>
    <main class="card">
        <div class="brand">
            <svg width="56" height="56" viewBox="0 0 64 64" aria-hidden="true">
                <defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0" stop-color="#22d3ee"/><stop offset="1" stop-color="#6366f1"/></linearGradient></defs>
                <path d="M44 6a28 28 0 1 0 14 40A22 22 0 0 1 44 6z" fill="url(#g)"/>
            </svg>
            <h1>Umbra</h1>
            <p>{{ __('Reset Password') }}</p>
        </div>

        @if ($errors->any())<div class="errors">{{ $errors->first() }}</div>@endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <label for="email">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">

            <label for="password">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">

            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">

            <button type="submit">{{ __('Reset Password') }}</button>
        </form>

        <div class="foot"><a href="{{ route('login') }}">&larr; {{ __('Back to login') }}</a></div>
    </main>
</body>
</html>
