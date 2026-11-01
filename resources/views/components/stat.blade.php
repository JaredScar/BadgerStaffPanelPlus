@props([
    'icon' => 'layer-group',
    'tone' => 'orange',
    'value',
    'label',
    'hint' => null,
])

<div {{ $attributes->merge(['class' => 'stat-card']) }}>
    <div class="stat-icon stat-icon-{{ $tone }}">
        <i class="fas fa-{{ $icon }}"></i>
    </div>
    <div class="stat-content">
        <h3 class="stat-number">{{ $value }}</h3>
        <p class="stat-label">{{ $label }}</p>
        @if ($hint)
            <small class="text-muted">{{ $hint }}</small>
        @endif
    </div>
</div>
