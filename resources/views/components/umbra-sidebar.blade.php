<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .side{position:fixed;top:58px;left:0;bottom:0;width:240px;padding:22px 14px;z-index:5;
        background:rgba(10,10,32,.6);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
        border-right:1px solid rgba(34,211,238,.15);overflow-y:auto}
    .side h4{margin:0 0 10px 12px;font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:#64748b}
    .side a{display:flex;align-items:center;gap:12px;padding:10px 12px;margin-bottom:4px;border-radius:10px;
        font-size:14px;color:#94a3b8;transition:.15s}
    .side a:hover{color:#fff;background:rgba(34,211,238,.1)}
    .side a.active{color:#22d3ee;background:rgba(34,211,238,.12)}
    .side .bi{font-size:17px;width:20px;text-align:center}
    @media(min-width:901px){
        main,.pagehead{width:calc(100% - 240px);margin-left:240px!important;margin-right:0!important}
    }
    @media(max-width:900px){
        .side{position:static;width:auto;display:flex;gap:6px;overflow-x:auto;padding:10px 16px;
            border-right:0;border-bottom:1px solid rgba(34,211,238,.15)}
        .side h4{display:none}
        .side a{margin:0;white-space:nowrap}
    }
</style>
<aside class="side">
    <h4>Menu</h4>
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('capsulas.crear') }}" class="{{ request()->routeIs('capsulas.*') ? 'active' : '' }}"><i class="bi bi-envelope-paper"></i> Letters</a>
    <a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"><i class="bi bi-person-circle"></i> Profile</a>
    <a href="{{ url('/') }}" target="_blank"><i class="bi bi-globe2"></i> Website</a>
</aside>
