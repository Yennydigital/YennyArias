@extends('admin.layout')
@section('content')
<div class="row" style="justify-content:space-between;margin-bottom:8px">
    <h1>Plan: {{ $plan->name }} <span class="tag">{{ $plan->is_active ? 'Activo' : 'Inactivo' }}</span></h1>
    <div class="row">
        <a class="btn" href="{{ route('admin.plans.index') }}">Volver</a>
        <a class="btn pri" href="{{ route('admin.plans.edit', $plan) }}">Editar</a>
    </div>
</div>

<div class="card">
    <table>
        <tr><th style="width:240px">Codigo</th><td>{{ $plan->code }}</td></tr>
        <tr><th>Nombre</th><td>{{ $plan->name }}</td></tr>
        <tr><th>Moneda</th><td>{{ $plan->currency }}</td></tr>
        <tr><th>Precio mensual</th><td>{{ number_format($plan->price_monthly, 2) }} {{ $plan->currency }}</td></tr>
        <tr><th>Precio anual</th><td>{{ $plan->price_yearly !== null ? number_format($plan->price_yearly, 2) . ' ' . $plan->currency : '-' }}</td></tr>
        <tr><th>Max. fotos</th><td>{{ $plan->max_photos }}</td></tr>
        <tr><th>Max. dias de la carta</th><td>{{ $plan->max_days }}</td></tr>
        <tr><th>Max. cartas activas</th><td>{{ $plan->max_letters ?? 'Sin tope' }}</td></tr>
        <tr><th>Permite musica</th><td>{{ $plan->allow_music ? 'Si' : 'No' }}</td></tr>
        <tr><th>Plantillas premium</th><td>{{ $plan->premium_templates ? 'Si' : 'No' }}</td></tr>
        <tr><th>Orden</th><td>{{ $plan->position }}</td></tr>
        <tr><th>Suscriptores activos</th><td>{{ $activeSubs }}</td></tr>
    </table>
</div>
@endsection
