<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} | Umbra</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,700,800&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;font-family:'Inter',system-ui,sans-serif;color:#e5e7eb;
            background:linear-gradient(rgba(5,5,25,.88),rgba(5,5,25,.94)),url('{{ asset('img/umbra-bg.gif') }}') center/cover no-repeat fixed}
        a{color:inherit;text-decoration:none}
        .nav{position:sticky;top:0;z-index:10;display:flex;align-items:center;justify-content:space-between;
            padding:14px 28px;background:rgba(10,10,32,.75);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
            border-bottom:1px solid rgba(34,211,238,.2)}
        .nav-left{display:flex;align-items:center;gap:28px}
        .brand{display:flex;align-items:center;gap:10px;font-weight:800;letter-spacing:.18em;text-transform:uppercase;color:#fff}
        .links{display:flex;gap:6px}
        .links a{padding:8px 14px;border-radius:999px;font-size:14px;color:#94a3b8;transition:.15s}
        .links a:hover{color:#fff;background:rgba(34,211,238,.1)}
        .links a.active{color:#22d3ee;background:rgba(34,211,238,.12)}
        details{position:relative}
        summary{list-style:none;cursor:pointer;padding:8px 14px;border-radius:999px;font-size:14px;
            border:1px solid rgba(148,163,184,.3);color:#e5e7eb}
        summary::-webkit-details-marker{display:none}
        summary:hover{border-color:#22d3ee}
        .menu{position:absolute;right:0;top:calc(100% + 8px);min-width:180px;padding:6px;border-radius:12px;
            background:rgba(10,10,32,.95);border:1px solid rgba(34,211,238,.25);box-shadow:0 10px 30px rgba(0,0,0,.5)}
        .menu a,.menu button{display:block;width:100%;text-align:left;padding:9px 12px;border-radius:8px;
            font-size:14px;color:#cbd5e1;background:none;border:0;cursor:pointer;font-family:inherit}
        .menu a:hover,.menu button:hover{background:rgba(34,211,238,.12);color:#fff}
        .wrap{max-width:1100px;margin:0 auto;padding:36px 24px 60px}
        h1.title{margin:0 0 6px;font-size:28px;font-weight:800;color:#fff}
        .sub{margin:0 0 28px;color:#94a3b8;font-size:15px}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:18px}
        .tile{display:block;padding:22px;border-radius:16px;background:rgba(10,10,32,.65);
            backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
            border:1px solid rgba(34,211,238,.2);transition:.2s}
        .tile:hover{border-color:#22d3ee;box-shadow:0 0 28px rgba(34,211,238,.18);transform:translateY(-2px)}
        .tile h3{margin:10px 0 6px;font-size:17px;color:#fff}
        .tile p{margin:0;font-size:14px;color:#94a3b8;line-height:1.5}
        .tile .ico{font-size:24px}
        .tile.ghost{border-style:dashed;border-color:rgba(148,163,184,.3);opacity:.7}
        .tile.ghost:hover{transform:none;box-shadow:none;border-color:rgba(148,163,184,.5)}
        @media(max-width:640px){.links{display:none}.nav{padding:12px 16px}}
    </style>
</head>
<body>
    <header class="nav">
        <div class="nav-left">
            <a href="{{ route('dashboard') }}" class="brand">
                <svg width="30" height="30" viewBox="0 0 64 64" aria-hidden="true">
                    <defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#22d3ee"/><stop offset="1" stop-color="#6366f1"/></linearGradient></defs>
                    <path d="M44 6a28 28 0 1 0 14 40A22 22 0 0 1 44 6z" fill="url(#g)"/>
                </svg>
                Umbra
            </a>
            <nav class="links">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ url('/') }}" target="_blank">Sitio</a>
            </nav>
        </div>
        <details>
            <summary>{{ Auth::user()->name }} &#9662;</summary>
            <div class="menu">
                <a href="{{ route('profile.show') }}">Perfil</a>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="submit">Cerrar sesión</button>
                </form>
            </div>
        </details>
    </header>
    <main class="wrap">{{ $slot }}</main>
</body>
</html>
