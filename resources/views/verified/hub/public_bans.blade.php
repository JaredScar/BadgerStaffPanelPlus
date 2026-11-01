<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials._html_header')
<body class="background-sizing gta-bg1">
    <div class="container-fluid master-contain">
        @include('_partials._toast')
        @if ($authenticated)
            @include('_partials._sidebar')
        @endif
        <div class="content-wrapper {{ $authenticated ? '' : 'content-wrapper-public' }}">
            <div class="page-header mb-4">
                <h1 class="page-title"><i class="fas fa-gavel me-2"></i>Public Bans</h1>
                <p class="page-description">Active and recent bans visible to the community</p>
            </div>
            <x-card title="Published bans" icon="gavel">
                <x-panel-table :headers="['Player', 'Reason', 'Duration', 'Staff', 'When']">
                    @forelse ($bans as $ban)
                        <tr>
                            <td>
                                @if ($authenticated)
                                    <a href="{{ route('PLAYER_PROFILE', ['playerId' => $ban->player_id]) }}">
                                        {{ optional($players[$ban->player_id] ?? null)->last_player_name ?? 'Unknown' }}
                                    </a>
                                @else
                                    {{ optional($players[$ban->player_id] ?? null)->last_player_name ?? 'Unknown' }}
                                @endif
                            </td>
                            <td>{{ $ban->reason }}</td>
                            <td>{{ $ban->expires ? ($ban->expiredDate ?: 'Temporary') : 'Permanent' }}</td>
                            <td>{{ optional($staff[$ban->staff_id] ?? null)->staff_username ?? 'Staff' }}</td>
                            <td>{{ \App\Support\PanelActivity::when($ban->created_at) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted">No bans have been published.</td></tr>
                    @endforelse
                </x-panel-table>
            </x-card>
        </div>
    </div>
    @include('_partials._html_footer')
</body>
</html>
