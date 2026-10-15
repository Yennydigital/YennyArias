<section class="position-relative min-vh-100 d-flex flex-column justify-content-center overflow-hidden text-white" id="home">
    <video autoplay loop muted playsinline class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" style="filter:saturate(1.8) contrast(1.15) brightness(1.05);">
        <source src="{{ asset('img/oso.mp4') }}" type="video/mp4">
    </video>
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background:linear-gradient(to bottom, rgba(5,5,30,.05), rgba(5,5,30,.55))"></div>


    <div class="container position-relative" style="max-width:1400px; padding-top:120px; padding-bottom:120px;">

        <span class="badge bg-primary px-3 py-2 rounded-pill">
            {{ __('Cápsulas del tiempo') }}
        </span>

        <h1 class="fw-bold mb-5 fade-in" style="font-size:clamp(2.5rem,8vw,6rem); line-height:.9; text-shadow:0 0 28px rgba(255,64,160,.75), 0 0 60px rgba(255,64,160,.45), 0 3px 8px rgba(0,0,0,.45);">
            <span class="d-block">{{ __('Escribí una carta hoy') }}</span>
            <span class="d-block">
                {{ __('para que se abra en el futuro') }}
            </span>
        </h1>

        <div class="row align-items-end g-5">
            <div class="col-lg-12 fade-in">
                <p class="fs-4 text-white" style="text-shadow:0 0 16px rgba(255,64,160,.7), 0 2px 6px rgba(0,0,0,.5);">
                {{ __('Escribí una carta, sellala con una fecha y dejala esperando a quien más querés.') }}
                </p>
                <div class="mt-5">
                    <div class="feature-check" style="text-shadow:0 0 12px rgba(255,64,160,.7), 0 2px 5px rgba(0,0,0,.5);">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ __('Cifrada: solo quien tenga el código puede leerla.') }}
                    </div>
                    <div class="feature-check" style="text-shadow:0 0 12px rgba(255,64,160,.7), 0 2px 5px rgba(0,0,0,.5);">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ __('Con fotos y música.') }}
                    </div>
                    <div class="feature-check" style="text-shadow:0 0 12px rgba(255,64,160,.7), 0 2px 5px rgba(0,0,0,.5);">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ __('Elegís cuándo se abre y cuánto se guarda.') }}
                    </div>
                    <div class="feature-check" style="text-shadow:0 0 12px rgba(255,64,160,.7), 0 2px 5px rgba(0,0,0,.5);">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ __('Podés hacer que se queme al leerla.') }}
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-flex flex-column flex-sm-row gap-3 fade-in">
                <a href="#projects" class="btn btn-primary btn-lg rounded-5 px-4">
                    {{ __('Cómo funciona') }}
                </a>
                <a href="{{ route('letters.create') }}" class="btn btn-outline-light btn-lg rounded-5 px-4">
                    {{ __('Escribir una carta') }}
                </a>
            </div>
        </div>
    </div>
</section>

