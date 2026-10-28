@extends('admin.layout')
@section('content')
<h1>{{ $isNew ? 'Nuevo plan' : 'Editar plan: ' . $plan->name }}</h1>
<form class="card" method="POST" action="{{ $isNew ? route('admin.plans.store') : route('admin.plans.update', $plan) }}">
    @csrf
    @unless ($isNew)
        @method('PUT')
    @endunless

    <label>Codigo (no se puede cambiar luego)</label>
    <input name="code" value="{{ old('code', $plan->code) }}" {{ $isNew ? '' : 'readonly' }} required>

    <label>Nombre</label>
    <input name="name" value="{{ old('name', $plan->name) }}" required>

    <div class="row">
        <div>
            <label>Moneda</label>
            <select name="currency">
                @foreach (['USD'] as $c)
                    <option value="{{ $c }}" @selected(old('currency', $plan->currency) === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Precio mensual</label>
            <input type="number" step="0.01" min="0" name="price_monthly" value="{{ old('price_monthly', $plan->price_monthly ?? 0) }}" required>
        </div>
        <div>
            <label>Precio anual (opcional)</label>
            <input type="number" step="0.01" min="0" name="price_yearly" value="{{ old('price_yearly', $plan->price_yearly) }}">
        </div>
    </div>

    <div class="row">
        <div>
            <label>Max. fotos</label>
            <input type="number" min="0" max="20" name="max_photos" value="{{ old('max_photos', $plan->max_photos ?? 3) }}" required>
        </div>
        <div>
            <label>Max. dias de la carta</label>
            <input type="number" min="1" max="365" name="max_days" value="{{ old('max_days', $plan->max_days ?? 30) }}" required>
        </div>
        <div>
            <label>Max. cartas activas (vacio = sin tope)</label>
            <input type="number" min="1" max="100000" name="max_letters" value="{{ old('max_letters', $plan->max_letters) }}" placeholder="Sin tope">
        </div>
        <div>
            <label>Orden</label>
            <input type="number" min="0" max="100" name="position" value="{{ old('position', $plan->position ?? 0) }}" required>
        </div>
    </div>

    <label class="chk"><input type="checkbox" name="allow_music" value="1" @checked(old('allow_music', $plan->allow_music))> Permite musica</label>
    <label class="chk"><input type="checkbox" name="premium_templates" value="1" @checked(old('premium_templates', $plan->premium_templates))> Incluye plantillas premium</label>
    <label class="chk"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true))> Activo</label>

    <div class="row" style="margin-top:20px">
        <button class="btn pri" type="submit">Guardar</button>
        <a class="btn" href="{{ route('admin.plans.index') }}">Cancelar</a>
    </div>
</form>
@endsection
