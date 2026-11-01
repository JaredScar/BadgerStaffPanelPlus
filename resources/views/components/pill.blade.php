@props([
    'href' => '#',
    'icon' => null,
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'bsp-pill']) }}>
    @if ($icon)
        <i class="fas fa-{{ $icon }}"></i>
    @endif
    {{ $slot }}
</a>
