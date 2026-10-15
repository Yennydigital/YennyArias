<x-umbra-layout title="Dashboard">
    <h1 class="title">Hola, {{ Auth::user()->name }}</h1>
    <p class="sub">Este es tu espacio en Umbra.</p>

    <div class="grid">
        <a href="{{ route('profile.show') }}" class="tile">
            <div class="ico">&#9881;</div>
            <h3>Perfil</h3>
            <p>Datos de tu cuenta, contraseña y seguridad.</p>
        </a>
        <a href="{{ url('/') }}" target="_blank" class="tile">
            <div class="ico">&#9728;</div>
            <h3>Ver sitio</h3>
            <p>Abre la landing pública en otra pestaña.</p>
        </a>
        @if (Route::has('api-tokens.index'))
        <a href="{{ route('api-tokens.index') }}" class="tile">
            <div class="ico">&#128273;</div>
            <h3>API tokens</h3>
            <p>Crea y revoca tokens de acceso.</p>
        </a>
        @endif
        <div class="tile ghost">
            <div class="ico">+</div>
            <h3>Nuevo módulo</h3>
            <p>Aquí irá lo próximo que construyamos.</p>
        </div>
    </div>
</x-umbra-layout>
