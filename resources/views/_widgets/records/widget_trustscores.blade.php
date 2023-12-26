<?php

use App\Models\Player;
use Illuminate\Support\Facades\Session;

$serverId = Session::get('server_id');
if (isset($data['selected_pid']))
    $playerData = Player::with("getPlayerData")->where('player_id', $data['selected_pid'])->where('server_id', $serverId)->get();
else
    $playerData = Player::with("getPlayerData")->where('server_id', $serverId)->get();
$data['data'] = $playerData;
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
                    <th>TrustScore</th>
                    <th>Joins</th>
                    <th>Last Join Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data['data'] as $player)
                    <tr>
                        <td>{{ $player->player_id }}</td>
                        <td>{{ $player->last_player_name }}</td>
                        <td>{{ $player->discord_id }}</td>
                        <td>{{ $player->getPlayerData->trust_score }}</td>
                        <td>{{ $player->getPlayerData->joins }}</td>
                        <td>{{ $player->getPlayerData->last_join_date }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
