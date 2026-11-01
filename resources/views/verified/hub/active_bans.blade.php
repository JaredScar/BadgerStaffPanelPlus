@extends('verified.layout')

@section('icon', 'fa-ban')
@section('title', 'Active Bans')
@section('subtitle', 'Permanent bans and temporary bans that have not expired')

@section('content')
    <div class="bsp-stat-grid">
        <x-stat icon="ban" tone="red" :value="$bans->count()" label="Active bans" />
    </div>

    <x-card title="Active bans" icon="gavel">
        <x-panel-table :headers="['Player', 'Reason', 'Duration', 'Staff', 'When']">
            @forelse ($bans as $ban)
                <tr>
                    <td>
                        <a href="{{ route('PLAYER_PROFILE', ['playerId' => $ban->player_id]) }}">
                            {{ optional($players[$ban->player_id] ?? null)->last_player_name ?? 'Unknown' }}
                        </a>
                    </td>
                    <td>{{ $ban->reason ?: '—' }}</td>
                    <td>{{ $ban->expires ? ($ban->expiredDate ?: 'Temporary') : 'Permanent' }}</td>
                    <td>{{ optional($staff[$ban->staff_id] ?? null)->staff_username ?? 'Staff' }}</td>
                    <td>{{ \App\Support\PanelActivity::when($ban->created_at) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No active bans on this server.</td></tr>
            @endforelse
        </x-panel-table>
    </x-card>
@endsection
