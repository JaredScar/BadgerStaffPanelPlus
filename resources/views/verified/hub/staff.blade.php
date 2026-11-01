@extends('verified.layout')

@section('icon', 'fa-user-shield')
@section('title', 'Staff Directory')
@section('subtitle', 'Staff accounts on this server')

@section('actions')
    <a class="btn btn-outline-secondary" href="{{ route('STAFF_PERFORMANCE') }}">Performance</a>
@endsection

@section('content')
    <x-card title="Staff" icon="users">
        <x-panel-table :headers="['Name', 'Role', 'Status', 'Email', 'Last active']">
            @forelse ($staff as $member)
                <tr>
                    <td>
                        <strong>{{ $member->staff_username }}</strong>
                        @if ($member->staff_discord)
                            <div class="text-muted small">{{ $member->staff_discord }}</div>
                        @endif
                    </td>
                    <td>{{ $member->role ?: 'staff' }}</td>
                    <td>{{ $member->status ?: 'active' }}</td>
                    <td>{{ $member->staff_email ?: '—' }}</td>
                    <td>{{ $member->last_active ? \App\Support\PanelActivity::when($member->last_active) : '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No staff accounts on this server.</td></tr>
            @endforelse
        </x-panel-table>
    </x-card>
@endsection