<section class="position-relative min-vh-100 d-flex flex-column justify-content-center overflow-hidden text-white" id="home">
    <video autoplay loop muted playsinline class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover">
        <source src="{{ asset('img/Hero_opt_3.mp4') }}" type="video/mp4">
    </video>
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background:rgba(5,5,30,.6)"></div>


    <div class="container position-relative" style="max-width:1400px; padding-top:120px; padding-bottom:120px;">

        <span class="badge bg-primary px-3 py-2 rounded-pill">
            {{ __('Cápsulas del tiempo') }}
        </span>

        <h1 class="fw-bold mb-5 fade-in" style="font-size:clamp(2.5rem,8vw,6rem); line-height:.9;">
            <span class="d-block">{{ __('Escribí una carta hoy') }}</span>
            <span class="d-block">
                {{ __('que se abre en') }}
                @if (app()->getLocale() === 'es')
<span class="position-relative d-inline-block">
                    <span id="animated-word" style="display:inline-block;min-width:6ch"></span>
                    <span class="position-absolute start-0 end-0 bottom-0" style="height:10px;background:#fff;opacity:.25;"></span>
                </span>
                @endif
            </span>
        </h1>

        <div class="row align-items-end g-5">
            <div class="col-lg-12 fade-in">
                <p class="fs-4 text-white-50">
                {{ __('Escribí una carta, sellala con una fecha y dejala esperando a quien más querés.') }}
                </p>
                <div class="mt-5">
                    <div class="feature-check">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ __('Cifrada: solo quien tenga el código puede leerla.') }}
                    </div>
                    <div class="feature-check">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ __('Con fotos y música.') }}
                    </div>
                    <div class="feature-check">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ __('Elegís cuándo se abre y cuánto se guarda.') }}
                    </div>
                    <div class="feature-check">
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

@push('scripts')
<script>
const words = {!! json_encode([__('una semana'), __('un año'), __('tu cumpleaños'), __('el futuro')], JSON_UNESCAPED_UNICODE) !!};
let index = 0;

function animateWord() {
  const container = document.getElementById("animated-word");
  if (!container) return;
  container.innerHTML = "";

  const word = words[index];

  word.split("").forEach((char, i) => {
    const span = document.createElement("span");
    span.textContent = char;
    span.style.opacity = "0";
    span.style.display = "inline-block";
    span.style.transform = "translateY(10px)";
    span.style.transition = "all 0.3s ease";
    span.style.transitionDelay = `${i * 50}ms`;

    container.appendChild(span);

    setTimeout(() => {
      span.style.opacity = "1";
      span.style.transform = "translateY(0)";
    }, 50);
  });

  index = (index + 1) % words.length;
}

animateWord();
setInterval(animateWord, 2500);
</script>
@endpush