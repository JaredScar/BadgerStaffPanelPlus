<?php
use App\Models\Player;
if (isset($data['selected_pid']))
    $tabData = Player::with('getPlayerData')->withCount(['kicks', 'bans', 'warns', 'notes', 'commends'])
        ->where('player_id', $data['selected_pid'])->get();
else
    $tabData = Player::with('getPlayerData')->withCount(['kicks', 'bans', 'warns', 'notes', 'commends'])->get();
$data['data'] = $tabData;
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <table id="table_players" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Player ID</th>
                        <th>Last Player Name</th>
                        <th>Discord</th>
                        <th>Play Time</th>
                        <th>Trust Score</th>
                        <th>Joins</th>
                        <th>Last Join Date</th>
                        <th>Commends</th>
                        <th>Warns</th>
                        <th>Kicks</th>
                        <th>Bans</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['data'] as $player)
                        <tr>
                            <td>{{ $player->player_id }}</td>
                            <td>{{ $player->getPlayerData->last_player_name }}</td>
                            <td>{{ $player->discord_id }}</td>
                            <td>{{ $player->getPlayerData->playtime }}</td>
                            <td>{{ $player->getPlayerData->trust_score }}</td>
                            <td>{{ $player->getPlayerData->joins }}</td>
                            <td>{{ $player->getPlayerData->last_join_date }}</td>
                            <td>{{ $player->commends_count }}</td>
                            <td>{{ $player->warns_count }}</td>
                            <td>{{ $player->kicks_count }}</td>
                            <td>{{ $player->bans_count }}</td>
                            <td>{{ $player->notes_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
