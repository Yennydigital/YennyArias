@extends('admin.layout')

@section('content')
    <h1>Visitas</h1>
    <p style="color:#94a3b8;margin:0 0 20px">Métricas de la página en tiempo real.</p>

    <div class="row" style="margin-bottom:20px">
        @foreach ([['Hoy', $today], ['Últimos 7 días', $last7], ['Únicos (30 días)', $uniques30], ['Total', $total]] as [$label, $value])
            <div class="card" style="flex:1;min-width:150px;margin-bottom:0">
                <div style="font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:.12em">{{ $label }}</div>
                <div style="font-size:32px;font-weight:800;color:#fff">{{ number_format($value) }}</div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <h3 style="margin:0 0 12px;color:#fff">Últimos 14 días</h3>
        @php $max = max(1, $daily->max('views')); @endphp
        @forelse ($daily as $row)
            <div style="display:flex;align-items:center;gap:12px;margin:6px 0;font-size:13px">
                <span style="width:60px;color:#94a3b8">{{ \Carbon\Carbon::parse($row->d)->format('d/m') }}</span>
                <div style="flex:1;background:rgba(148,163,184,.15);border-radius:999px;height:10px">
                    <div style="width:{{ ($row->views / $max) * 100 }}%;background:#22d3ee;height:10px;border-radius:999px"></div>
                </div>
                <span style="width:150px;text-align:right;color:#e5e7eb">{{ $row->views }} vistas / {{ $row->visitors }} únicos</span>
            </div>
        @empty
            <p style="color:#94a3b8">Todavía no hay datos.</p>
        @endforelse
    </div>

    <div class="row" style="align-items:stretch">
        <div class="card" style="flex:1;min-width:280px">
            <h3 style="margin:0;color:#fff">Páginas más vistas</h3>
            <table>
                <tr><th>Ruta</th><th style="text-align:right">Vistas</th></tr>
                @foreach ($topPages as $p)
                    <tr><td>{{ $p->path }}</td><td style="text-align:right">{{ $p->views }}</td></tr>
                @endforeach
            </table>
        </div>
        <div class="card" style="flex:1;min-width:280px">
            <h3 style="margin:0;color:#fff">De dónde llegan</h3>
            <table>
                <tr><th>Origen</th><th style="text-align:right">Vistas</th></tr>
                @foreach ($topReferers as $r)
                    <tr><td style="max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $r->referer }}</td><td style="text-align:right">{{ $r->views }}</td></tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
