@props([
    'href' => null,         // Jeśli podamy 'href', komponent stanie się linkiem <a>
    'variant' => 'primary', // Domyślny wygląd to 'primary'
    'tag' => null           // Opcjonalnie możemy wymusić bycie linkiem lub przyciskiem
])

@php
    // Logika wyboru tagu: jeśli jest 'href', to na 99% chcemy link <a>.
    // W przeciwnym wypadku to będzie przycisk <button>.
    $tag = $tag ?? ($href ? 'a' : 'button');

    // Dynamiczne budowanie listy klas CSS
    $classes = 'btn btn-' . $variant;
@endphp

@if ($tag === 'a')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }} {{-- Tutaj trafi tekst przycisku --}}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }} {{-- Tutaj trafi tekst przycisku --}}
    </button>
@endif