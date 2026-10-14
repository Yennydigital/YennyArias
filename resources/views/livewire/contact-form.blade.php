<div>
    @if ($sent)
        <div class="alert alert-success rounded-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ __('¡Gracias! Recibí tu mensaje y te respondo pronto.') }}
        </div>
    @else
        <form wire:submit="send" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="c-name">{{ __('Nombre') }}</label>
                    <input id="c-name" type="text" wire:model="name" class="form-control form-control-lg @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="c-email">{{ __('Tu email') }}</label>
                    <input id="c-email" type="email" wire:model="email" class="form-control form-control-lg @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="c-message">{{ __('Mensaje') }}</label>
                    <textarea id="c-message" rows="5" wire:model="message" class="form-control form-control-lg @error('message') is-invalid @enderror"></textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div style="position:absolute;left:-9999px;" aria-hidden="true">
                    <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-lg rounded-5 px-5" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ __('Enviar mensaje') }}</span>
                        <span wire:loading>{{ __('Enviando...') }}</span>
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
