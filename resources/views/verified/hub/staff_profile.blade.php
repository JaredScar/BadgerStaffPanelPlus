@extends('verified.layout')

@section('header')
    <a href="{{ route('STAFF_DIRECTORY') }}" class="profile-back">
        <i class="fas fa-arrow-left"></i> Back to Staff
    </a>
@endsection

@section('content')
    <div class="profile-hero">
        <div class="profile-hero-left">
            <div class="profile-avatar">{{ \App\Support\PanelActivity::initials($member->staff_username) }}</div>
            <div class="profile-hero-info">
                <div class="profile-name">{{ $member->staff_username }}</div>
                <div class="profile-ids">
                    {{ $member->role ?: 'staff' }}
                    @if ($member->staff_email) · {{ $member->staff_email }} @endif
                    @if ($member->staff_discord) · discord:{{ $member->staff_discord }} @endif
                </div>
                <div class="profile-chips">
                    @foreach ($records as $label => $rows)
                        <span class="profile-chip">{{ $rows->count() }} {{ $label }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="bsp-stat-grid">
        <x-stat icon="ban" tone="red" :value="$records['Bans']->count()" label="Bans" />
        <x-stat icon="exclamation-triangle" tone="orange" :value="$records['Warns']->count()" label="Warns" />
        <x-stat icon="shoe-prints" tone="orange" :value="$records['Kicks']->count()" label="Kicks" />
        <x-stat icon="sticky-note" tone="blue" :value="$records['Notes']->count()" label="Notes" />
        <x-stat icon="thumbs-up" tone="green" :value="$records['Commends']->count()" label="Commends" />
    </div>

    @foreach ($records as $label => $rows)
        @php $textKey = $label === 'Notes' ? 'note' : 'reason'; @endphp
        <x-card :title="$label.' ('.$rows->count().')'" class="mb-4">
            <x-panel-table :headers="['Player', 'Detail', 'When']">
                @forelse ($rows as $row)
                    <tr>
                        <td>
                            <a href="{{ route('PLAYER_PROFILE', ['playerId' => $row->player_id]) }}">
                                {{ optional($players[$row->player_id] ?? null)->last_player_name ?? 'Unknown' }}
                            </a>
                        </td>
                        <td>
                            {{ $row->{$textKey} ?: '—' }}
                            @if ($label === 'Bans')
                                · {{ $row->expires ? ($row->expiredDate ?: 'Temporary') : 'Permanent' }}
                            @endif
                        </td>
                        <td>{{ \App\Support\PanelActivity::when($row->created_at) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-muted">No {{ strtolower($label) }} from this staff member.</td></tr>
                @endforelse
            </x-panel-table>
        </x-card>
    @endforeach
@endsection
