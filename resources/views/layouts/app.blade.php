<x-umbra-layout :jet="true" title="Cuenta">
    <x-slot name="header">{{ $header ?? '' }}</x-slot>
    {{ $slot }}
</x-umbra-layout>
