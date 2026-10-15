<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-md-6">
                <h5 class="fw-bold">Umbra</h5>
                <p class="text-light mb-0">{{ __('Cartas que llegan a tu correo') }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('letters.create') }}" class="btn btn-primary rounded-5 px-4">{{ __('Escribir una carta') }}</a>
            </div>
        </div>
        <hr class="border-light my-4">
        <small class="text-light">© {{ date('Y') }} Umbra</small>
    </div>
</footer>
