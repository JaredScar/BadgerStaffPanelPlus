@props([
    'title' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'panel-card']) }}>
    @if ($title)
        <div class="panel-card-header">
            @if ($icon)
                <i class="fas fa-{{ $icon }} me-2"></i>
            @endif
            {{ $title }}
        </div>
    @endif
    {{ $slot }}
</div>
