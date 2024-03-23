<?php
use App\Models\Warn;
if (isset($data['selected_pid']))
    $kickData = Warn::with('getPlayer')->with('getStaff')->where('player_id', $data['selected_pid'])->get();
else
    $kickData = Warn::with('getPlayer')->with('getStaff')->get();
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
                @foreach ($data["data"] as $warn)
                    <tr>
                        <td>{{ $warn->player_id }}</td>
                        <td>{{ $warn->getPlayer->last_player_name }}</td>
                        <td>{{ $warn->getPlayer->discord_id }}</td>
                        <td>{{ $warn->reason }}</td>
                        <td>{{ $warn->created_at }}</td>
                        <td>{{ $warn->getStaff->staff_username }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
