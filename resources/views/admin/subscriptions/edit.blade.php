@extends('admin.layout')
@section('content')
<h1>Editar suscripcion #{{ $sub->id }}</h1>
<form class="card" method="POST" action="{{ route('admin.subscriptions.update', $sub) }}">
    @csrf
    @method('PUT')

    <label>Usuario (no se puede cambiar)</label>
    <input value="{{ $sub->user->email ?? ('#' . $sub->user_id) }}" readonly style="width:100%">

    <div class="row">
        <div>
            <label>Plan</label>
            <select name="plan_id">
                @foreach ($plans as $p)
                    <option value="{{ $p->id }}" @selected((int) old('plan_id', $sub->plan_id) === $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Ciclo</label>
            <select name="billing_cycle">
                @foreach (['monthly' => 'Mensual', 'yearly' => 'Anual'] as $k => $l)
                    <option value="{{ $k }}" @selected(old('billing_cycle', $sub->billing_cycle) === $k)>{{ $l }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Estado</label>
            <select name="status">
                @foreach (['active' => 'Activa', 'cancelled' => 'Cancelada', 'expired' => 'Vencida'] as $k => $l)
                    <option value="{{ $k }}" @selected(old('status', $sub->status) === $k)>{{ $l }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div>
            <label>Inicio del periodo</label>
            <input type="datetime-local" name="current_period_start" required
                   value="{{ old('current_period_start', optional($sub->current_period_start ?? now())->format('Y-m-d\TH:i')) }}">
        </div>
        <div>
            <label>Vence</label>
            <input type="datetime-local" name="current_period_end" required
                   value="{{ old('current_period_end', optional($sub->current_period_end)->format('Y-m-d\TH:i')) }}">
        </div>
    </div>

    @if ($sub->cancelled_at)
        <p style="opacity:.6;margin-top:14px">Cancelada el {{ $sub->cancelled_at->format('Y-m-d H:i') }}</p>
    @endif

    <div class="row" style="margin-top:20px">
        <button class="btn pri" type="submit">Guardar</button>
        <a class="btn" href="{{ route('admin.subscriptions.index') }}">Volver</a>
    </div>
</form>
@endsection
