@extends('verified.layout')

@section('icon', 'fa-hourglass-half')
@section('title', 'Bans Ending Soon')
@section('subtitle', 'Temporary bans that expire within the next 7 days')

@section('content')
    <div class="bsp-stat-grid">
        <x-stat icon="clock" tone="orange" :value="$withinDay->count()" label="Next 24 hours" />
        <x-stat icon="calendar-week" tone="blue" :value="$withinWeek->count()" label="Rest of the week" />
    </div>

    @foreach (['Next 24 hours' => $withinDay, 'Rest of the week' => $withinWeek] as $heading => $rows)
        <x-card :title="$heading" icon="hourglass-half" class="mb-4">
            <x-panel-table :headers="['Player', 'Reason', 'Expires', 'Staff', '']">
                @forelse ($rows as $ban)
                    <tr>
                        <td>
                            <a href="{{ route('PLAYER_PROFILE', ['playerId' => $ban->player_id]) }}">
                                {{ optional($players[$ban->player_id] ?? null)->last_player_name ?? 'Unknown' }}
                            </a>
                        </td>
                        <td>{{ $ban->reason ?: '—' }}</td>
                        <td>
                            {{ $ban->expiredDate }}
                            <div class="text-muted small">{{ \Carbon\Carbon::parse($ban->expiredDate)->diffForHumans() }}</div>
                        </td>
                        <td>
                            @if ($member = ($staff[$ban->staff_id] ?? null))
                                <a href="{{ route('STAFF_PROFILE', ['staffId' => $member->staff_id]) }}">{{ $member->staff_username }}</a>
                            @else
                                Staff
                            @endif
                        </td>
                        <td>@include('verified._partials.revoke_form', ['type' => 'ban', 'id' => $ban->id, 'label' => 'Unban'])</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">No bans in this window.</td></tr>
                @endforelse
            </x-panel-table>
        </x-card>
    @endforeach
@endsection
