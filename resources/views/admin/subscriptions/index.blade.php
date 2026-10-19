@extends('admin.layout')
@section('content')
<h1>Suscripciones</h1>

<form class="card" method="POST" action="{{ route('admin.subscriptions.store') }}">
    @csrf
    <strong>Dar un plan a un usuario</strong>
    <div class="row">
        <div><label>Email del usuario</label><input type="email" name="email" value="{{ old('email') }}" required></div>
        <div>
            <label>Plan</label>
            <select name="plan_id">
                @foreach ($plans as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Ciclo</label>
            <select name="billing_cycle"><option value="monthly">Mensual</option><option value="yearly">Anual</option></select>
        </div>
        <div><label>Dias</label><input type="number" name="days" min="1" max="730" value="30" style="width:90px" required></div>
        <button class="btn pri" type="submit">Crear</button>
    </div>
</form>

<form method="GET" class="row">
    <input type="text" name="q" value="{{ $q }}" placeholder="Buscar por email">
    <button class="btn" type="submit">Buscar</button>
</form>

<table>
    <tr><th>Usuario</th><th>Plan</th><th>Ciclo</th><th>Estado</th><th>Vence</th><th>Acciones</th></tr>
    @forelse ($subs as $s)
        <tr>
            <td>{{ $s->user->email ?? ('#' . $s->user_id) }}</td>
            <td>{{ $s->plan->name ?? '-' }}</td>
            <td>{{ $s->billing_cycle }}</td>
            <td><span class="tag">{{ $s->status }}{{ $s->status === 'active' && $s->current_period_end && $s->current_period_end->isPast() ? ' (vencida)' : '' }}</span></td>
            <td>{{ $s->current_period_end ? $s->current_period_end->format('Y-m-d H:i') : '-' }}</td>
            <td>
                <a class="btn" href="{{ route('admin.subscriptions.edit', $s) }}" style="margin-bottom:6px">Editar</a>
                <form method="POST" action="{{ route('admin.subscriptions.extend', $s) }}" class="row" style="gap:6px">
                    @csrf
                    <input type="number" name="days" min="1" max="730" value="30" style="width:70px">
                    <button class="btn" type="submit">Extender</button>
                </form>
                @if ($s->status === 'active')
                    <form method="POST" action="{{ route('admin.subscriptions.cancel', $s) }}" style="margin-top:6px">
                        @csrf
                        <button class="btn danger" type="submit">Cancelar</button>
                    </form>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="6" style="opacity:.6">Sin suscripciones.</td></tr>
    @endforelse
</table>
@endsection
