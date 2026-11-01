@props([
    'badge' => 'bg-secondary',
    'type' => 'Event',
    'time' => '',
])

<div {{ $attributes->merge(['class' => 'activity-row']) }}>
    <span class="badge {{ $badge }}">{{ $type }}</span>
    <div>
        {{ $slot }}
        @if ($time)
            <small>{{ $time }}</small>
        @endif
    </div>
</div>
