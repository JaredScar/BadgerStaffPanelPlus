@extends('verified.layout')

@section('icon', 'fa-search')
@section('title', 'Player Lookup')
@section('subtitle', 'Search by name, license, Steam, Discord, IP, or panel ID')

@section('content')
    <x-card class="mb-4">
        <form method="get" action="{{ route('LOOKUP') }}" class="p-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-9">
                    <label class="form-label">Search</label>
                    <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="WolfBadger, license, discord id..." autofocus>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" type="submit"><i class="fas fa-search me-1"></i> Search</button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card :title="$q ? 'Results for “'.$q.'”' : 'Results'" icon="users">
        <x-panel-table :headers="['Player', 'License', 'Discord', 'Trust', 'Last seen']">
            @forelse ($results as $player)
                @php $pd = $playerData[$player->player_id] ?? null; @endphp
                <tr>
                    <td>
                        <a href="{{ route('PLAYER_PROFILE', ['playerId' => $player->player_id]) }}">{{ $player->last_player_name ?: 'Unknown' }}</a>
                        <div class="text-muted small">#{{ $player->player_id }}</div>
                    </td>
                    <td class="font-monospace">{{ $player->game_license ?: '—' }}</td>
                    <td class="font-monospace">{{ $player->discord_id ?: '—' }}</td>
                    <td>{{ $pd->trust_score ?? '—' }}</td>
                    <td>{{ $pd && $pd->last_join_date ? \App\Support\PanelActivity::when($pd->last_join_date) : 'Never' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-muted">
                        {{ $q === '' ? 'Enter a name or identifier to look someone up.' : 'No players matched that search.' }}
                    </td>
                </tr>
            @endforelse
        </x-panel-table>
    </x-card>
@endsection
