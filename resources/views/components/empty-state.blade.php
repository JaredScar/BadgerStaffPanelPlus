@props([
    'icon' => 'inbox',
    'title' => 'Nothing here yet',
    'text' => '',
])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <i class="fas fa-{{ $icon }} fa-3x"></i>
    <h4>{{ $title }}</h4>
    @if ($text)
        <p>{{ $text }}</p>
    @endif
</div>
