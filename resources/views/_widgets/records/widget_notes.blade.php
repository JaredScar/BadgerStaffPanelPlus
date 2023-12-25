<?php
use App\Models\Note;
if (isset($data['selected_pid']))
    $tabData = Note::with('getPlayer')->with('getStaff')->where('player_id', $data['selected_pid'])->get();
else
    $tabData = Note::with('getPlayer')->with('getStaff')->get();
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
                    <th>Note</th>
                    <th>DateTime</th>
                    <th>Staff Member</th>
                </tr>
                </thead>
                <tbody>
                <!-- TODO Get the data from PHP and put it here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
