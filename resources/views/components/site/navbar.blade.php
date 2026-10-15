@php $other = app()->getLocale() === 'es' ? 'en' : 'es'; @endphp
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top py-2">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4 text-dark" href="{{ route('index') }}">Umbra</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu"
            aria-controls="menu" aria-label="{{ __('Abrir menú') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link fs-6 px-3 text-dark" href="#home">{{ __('Inicio') }}</a></li>
                <li class="nav-item"><a class="nav-link fs-6 px-3 text-dark" href="#about">{{ __('Qué es Umbra') }}</a></li>
                <li class="nav-item"><a class="nav-link fs-6 px-3 text-dark" href="#projects">{{ __('Cartas') }}</a></li>
                <li class="nav-item"><a class="nav-link fs-6 px-3 text-dark" href="#contact">{{ __('Suscribirme') }}</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('lang.switch', $other) }}" class="btn btn-outline-secondary rounded-5 px-3"
                   aria-label="{{ __('Cambiar idioma') }}">
                    <i class="bi bi-translate me-1"></i> {{ strtoupper($other) }}
                </a>
                <a href="#contact" class="btn btn-primary rounded-5 px-4">{{ __('Suscribirme') }}</a>
            </div>
        </div>
    </div>
</nav>
