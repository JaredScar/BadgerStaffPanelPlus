<?php
use App\Models\Kick;
if (isset($data['selected_pid']))
    $kickData = Kick::with('getPlayer')->with('getStaff')->where('player_id', $data['selected_pid'])->get();
else
    $kickData = Kick::with('getPlayer')->with('getStaff')->get();
$data['data'] = $kickData;
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
                    <th>Reason</th>
                    <th>DateTime</th>
                    <th>Staff Member</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($data["data"] as $kick)
                    <tr>
                        <td>{{ $kick->player_id }}</td>
                        <td>{{ $kick->getPlayer->last_player_name }}</td>
                        <td>{{ $kick->getPlayer->discord_id }}</td>
                        <td>{{ $kick->reason }}</td>
                        <td>{{ $kick->created_at }}</td>
                        <td>{{ $kick->getStaff->staff_username }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
