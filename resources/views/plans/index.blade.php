<x-umbra-layout title="Planes">
@if (session('billing_ok'))<div style="max-width:680px;margin:0 auto 18px;padding:10px 14px;border-radius:12px;border:1px solid #34d399;background:rgba(52,211,153,.12)">{{ session('billing_ok') }}</div>@endif
@if (session('billing_err'))<div style="max-width:680px;margin:0 auto 18px;padding:10px 14px;border-radius:12px;border:1px solid #f87171;background:rgba(248,113,113,.12)">{{ session('billing_err') }}</div>@endif

@include('plans._cards', ['mode' => 'auth'])

@if ($sub)
    <p class="pl-exp">Tu plan {{ $sub->plan->name ?? '' }} está vigente hasta el {{ $sub->current_period_end->format('d/m/Y') }}.</p>
@endif
</x-umbra-layout>
