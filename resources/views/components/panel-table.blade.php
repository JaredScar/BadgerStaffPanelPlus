@props([
    'headers' => [],
])

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-hover mb-0 profile-table']) }}>
        @if (count($headers))
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
