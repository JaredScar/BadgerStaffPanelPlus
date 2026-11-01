@extends('verified.layout')

@section('icon', 'fa-eye')
@section('title', 'Watchlist')
@section('subtitle', 'Players flagged for extra staff attention')

@section('content')
    <x-card title="Add to watchlist" icon="plus" class="mb-4">
        <form method="post" action="{{ route('WATCHLIST_ADD') }}" class="p-4">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Player</label>
                    <select name="player_id" class="form-select" required>
                        <option value="">Select a player</option>
                        @foreach ($allPlayers as $player)
                            <option value="{{ $player->player_id }}">{{ $player->last_player_name }} #{{ $player->player_id }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Reason</label>
                    <input type="text" name="reason" class="form-control" maxlength="255" placeholder="Why should staff watch this player?">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">Add</button>
                </div>
            </div>
        </form>
    </x-card>

    <x-card title="Flagged players" icon="eye">
        <x-panel-table :headers="['Player', 'Reason', 'Added by', 'When', '']">
            @forelse ($items as $item)
                <tr>
                    <td>
                        <a href="{{ route('PLAYER_PROFILE', ['playerId' => $item->player_id]) }}">
                            {{ optional($players[$item->player_id] ?? null)->last_player_name ?? 'Unknown' }}
                        </a>
                    </td>
                    <td>{{ $item->reason }}</td>
                    <td>{{ optional($staff[$item->staff_id] ?? null)->staff_username ?? 'Staff' }}</td>
                    <td>{{ \App\Support\PanelActivity::when($item->created_at) }}</td>
                    <td>
                        <form method="post" action="{{ route('WATCHLIST_REMOVE', ['id' => $item->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">The watchlist is empty.</td></tr>
            @endforelse
        </x-panel-table>
    </x-card>
@endsection
