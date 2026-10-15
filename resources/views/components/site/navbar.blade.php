@php $other = app()->getLocale() === 'es' ? 'en' : 'es'; @endphp
<nav class="navbar navbar-expand-lg sticky-top py-2" data-bs-theme="dark" style="background:#05051e;">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4 text-white" href="{{ route('index') }}">Umbra</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu"
            aria-controls="menu" aria-label="{{ __('Abrir menú') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link fs-6 px-3 text-white" href="#home">{{ __('Inicio') }}</a></li>
                <li class="nav-item"><a class="nav-link fs-6 px-3 text-white" href="#about">{{ __('Cómo funciona') }}</a></li>
                <li class="nav-item"><a class="nav-link fs-6 px-3 text-white" href="#projects">{{ __('Plantillas') }}</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('lang.switch', $other) }}" class="btn btn-outline-light rounded-5 px-3"
                   aria-label="{{ __('Cambiar idioma') }}">
                    <i class="bi bi-translate me-1"></i> {{ strtoupper($other) }}
                </a>
                <a href="{{ route('letters.create') }}" class="btn btn-primary rounded-5 px-4">{{ __('Escribir una carta') }}</a>
            </div>
        </div>
    </div>
</nav>
