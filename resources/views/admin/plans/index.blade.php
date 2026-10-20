@extends('admin.layout')
@section('content')
<div class="row" style="justify-content:space-between;margin-bottom:8px">
    <h1>Planes</h1>
    <a class="btn pri" href="{{ route('admin.plans.create') }}">Nuevo plan</a>
</div>
<table>
    <tr><th>Codigo</th><th>Nombre</th><th>Mensual</th><th>Anual</th><th>Fotos</th><th>Dias</th><th>Musica</th><th>Premium</th><th>Estado</th><th></th></tr>
    @foreach ($plans as $p)
        <tr>
            <td>{{ $p->code }}</td>
            <td><a href="{{ route('admin.plans.show', $p) }}" style="color:#c4b5fd">{{ $p->name }}</a></td>
            <td>{{ number_format($p->price_monthly, 2) }} {{ $p->currency }}</td>
            <td>{{ $p->price_yearly !== null ? number_format($p->price_yearly, 2) . ' ' . $p->currency : '-' }}</td>
            <td>{{ $p->max_photos }}</td>
            <td>{{ $p->max_days }}</td>
            <td>{{ $p->allow_music ? 'Si' : 'No' }}</td>
            <td>{{ $p->premium_templates ? 'Si' : 'No' }}</td>
            <td><span class="tag">{{ $p->is_active ? 'Activo' : 'Inactivo' }}</span></td>
            <td><a class="btn" href="{{ route('admin.plans.edit', $p) }}">Editar</a></td>
        </tr>
    @endforeach
</table>
@endsection
