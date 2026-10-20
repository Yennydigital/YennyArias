<x-umbra-layout title="Admin">
<style>
.adm h1{margin:0 0 6px;font-size:28px;font-weight:800;color:#fff}
.adm table{width:100%;border-collapse:collapse;margin:12px 0 24px}
.adm th,.adm td{text-align:left;padding:10px 12px;border-bottom:1px solid rgba(148,163,184,.18);vertical-align:middle}
.adm th{color:#64748b;font-weight:600;font-size:11px;text-transform:uppercase;letter-spacing:.12em}
.adm input,.adm select{background:rgba(15,15,45,.8);color:#fff;border:1px solid rgba(148,163,184,.3);border-radius:10px;padding:9px 12px;font:inherit}
.adm input:focus,.adm select:focus{border-color:#22d3ee;box-shadow:0 0 0 3px rgba(34,211,238,.2);outline:none}
.adm input[readonly]{opacity:.6}
.adm label{display:block;margin:14px 0 6px;font-size:13px;color:#94a3b8}
.adm .row{display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end}
.adm .btn{display:inline-block;padding:9px 16px;border-radius:999px;border:1px solid rgba(148,163,184,.3);background:none;color:#e5e7eb;cursor:pointer;font:inherit;font-size:14px;transition:.15s}
.adm .btn:hover{border-color:#22d3ee;color:#fff}
.adm .pri{background:#22d3ee;border-color:#22d3ee;color:#04111f;font-weight:600}
.adm .pri:hover{background:#67e8f9;border-color:#67e8f9;color:#04111f}
.adm .danger{border-color:#f87171;color:#f87171}
.adm .danger:hover{background:rgba(248,113,113,.12);color:#fca5a5;border-color:#f87171}
.adm .ok{background:rgba(52,211,153,.12);border:1px solid #34d399;padding:10px 14px;border-radius:12px;margin-bottom:16px}
.adm .err{background:rgba(248,113,113,.12);border:1px solid #f87171;padding:10px 14px;border-radius:12px;margin-bottom:16px}
.adm .tag{display:inline-block;padding:2px 10px;border-radius:999px;font-size:11px;background:rgba(34,211,238,.12);color:#22d3ee}
.adm .card{background:rgba(10,10,32,.65);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);border:1px solid rgba(34,211,238,.2);border-radius:16px;padding:20px;margin-bottom:20px}
.adm .chk{display:flex;gap:8px;align-items:center;margin:12px 0 0}
.adm .chk input{width:auto}
.adm a:not(.btn){color:#22d3ee}
</style>
<div class="adm">
    @if (session('ok'))
        <div class="ok">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
        <div class="err">
            @foreach ($errors->all() as $e)
                <div>{{ $e }}</div>
            @endforeach
        </div>
    @endif
    @yield('content')
</div>
</x-umbra-layout>
