<?php
use App\Models\Note;
use App\Models\Player;
use App\Models\Staff;
use App\Support\PanelActivity;
use Illuminate\Support\Facades\Session;

$serverId = Session::get('server_id');
$query = Note::query()->orderByDesc('created_at');
if (isset($data['selected_pid'])) {
    $query->where('player_id', $data['selected_pid']);
}
if ($serverId) {
    $query->where('server_id', $serverId);
}
$tabData = $query->get();
$players = Player::query()->whereIn('player_id', $tabData->pluck('player_id'))->get()->keyBy('player_id');
$staff = Staff::query()->whereIn('staff_id', $tabData->pluck('staff_id'))->get()->keyBy('staff_id');
$data['data'] = $tabData;
?>
<x-card title="Notes" icon="sticky-note">
    <x-panel-table :headers="['Player', 'Note', 'Staff', 'When']">
        @forelse ($tabData as $note)
            @php
                $player = $players[$note->player_id] ?? null;
            @endphp
            <tr>
                <td>
                    <a href="{{ route('PLAYER_PROFILE', ['playerId' => $note->player_id]) }}">
                        {{ $player->last_player_name ?? 'Unknown' }}
                    </a>
                </td>
                <td>{{ $note->note }}</td>
                <td>{{ optional($staff[$note->staff_id] ?? null)->staff_username ?? 'Staff' }}</td>
                <td>{{ PanelActivity::when($note->created_at) }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-muted">No notes yet.</td></tr>
        @endforelse
    </x-panel-table>
</x-card>
