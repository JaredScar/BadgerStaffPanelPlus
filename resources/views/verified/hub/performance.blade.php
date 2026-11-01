@extends('verified.layout')

@section('icon', 'fa-trophy')
@section('title', 'Staff Performance')
@section('subtitle', 'Action counts across the current server')

@section('content')
    <x-card title="Staff totals" icon="trophy">
        <x-panel-table :headers="['Staff', 'Role', 'Warns', 'Kicks', 'Bans', 'Commends', 'Notes', 'Total']">
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $row['staff']->staff_username }}</td>
                    <td>{{ $row['staff']->role ?: 'staff' }}</td>
                    <td>{{ $row['warns'] }}</td>
                    <td>{{ $row['kicks'] }}</td>
                    <td>{{ $row['bans'] }}</td>
                    <td>{{ $row['commends'] }}</td>
                    <td>{{ $row['notes'] }}</td>
                    <td><strong>{{ $row['total'] }}</strong></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-muted">No staff members found.</td></tr>
            @endforelse
        </x-panel-table>
    </x-card>
@endsection
