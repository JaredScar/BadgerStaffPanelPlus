<?php

use App\Models\Ban;
use App\Models\Warn;
use App\Models\Kick;
use App\Models\Commend;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

$serverId = Session::get('server_id');

// Get all records from different models
$bans = Ban::with('getPlayer')->with('getStaff')->get()->map(function($ban) {
    return [
        'type' => 'Ban',
        'type_class' => 'ban',
        'icon' => 'fas fa-ban',
        'player_id' => $ban->player_id,
        'player_name' => $ban->getPlayer->last_player_name ?? 'Unknown',
        'discord' => $ban->getPlayer->discord_id ?? 'N/A',
        'reason' => $ban->reason,
        'date_time' => $ban->created_at,
        'staff_member' => $ban->getStaff->staff_username ?? 'System',
        'additional_info' => $ban->duration ? "Duration: " . $ban->duration : "Duration: Permanent",
        'severity' => $ban->duration ? 'temporary' : 'permanent'
    ];
});

$warns = Warn::with('getPlayer')->with('getStaff')->get()->map(function($warn) {
    return [
        'type' => 'Warn',
        'type_class' => 'warn',
        'icon' => 'fas fa-exclamation-triangle',
        'player_id' => $warn->player_id,
        'player_name' => $warn->getPlayer->last_player_name ?? 'Unknown',
        'discord' => $warn->getPlayer->discord_id ?? 'N/A',
        'reason' => $warn->reason,
        'date_time' => $warn->created_at,
        'staff_member' => $warn->getStaff->staff_username ?? 'System',
        'additional_info' => "Severity: " . ($warn->severity ?? 'Medium'),
        'severity' => strtolower($warn->severity ?? 'medium')
    ];
});

$kicks = Kick::with('getPlayer')->with('getStaff')->get()->map(function($kick) {
    return [
        'type' => 'Kick',
        'type_class' => 'kick',
        'icon' => 'fas fa-shoe-prints',
        'player_id' => $kick->player_id,
        'player_name' => $kick->getPlayer->last_player_name ?? 'Unknown',
        'discord' => $kick->getPlayer->discord_id ?? 'N/A',
        'reason' => $kick->reason,
        'date_time' => $kick->created_at,
        'staff_member' => $kick->getStaff->staff_username ?? 'System',
        'additional_info' => "Action: Kicked from server",
        'severity' => 'standard'
    ];
});

$commends = Commend::with('getPlayer')->with('getStaff')->get()->map(function($commend) {
    return [
        'type' => 'Commend',
        'type_class' => 'commend',
        'icon' => 'fas fa-thumbs-up',
        'player_id' => $commend->player_id,
        'player_name' => $commend->getPlayer->last_player_name ?? 'Unknown',
        'discord' => $commend->getPlayer->discord_id ?? 'N/A',
        'reason' => $commend->reason,
        'date_time' => $commend->created_at,
        'staff_member' => $commend->getStaff->staff_username ?? 'System',
        'additional_info' => "Type: Positive feedback",
        'severity' => 'positive'
    ];
});

// Combine all records
$allRecords = collect()
    ->merge($bans)
    ->merge($warns)
    ->merge($kicks)
    ->merge($commends)
    ->sortByDesc('date_time');

// Calculate statistics
$totalBans = $bans->count();
$totalWarns = $warns->count();
$totalKicks = $kicks->count();
$totalCommends = $commends->count();
$totalRecords = $allRecords->count();

?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-5col">
        <div class="stat-card">
            <div class="stat-icon stat-icon-red">
                <i class="fas fa-ban"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $totalBans }}</h3>
                <p class="stat-label">Bans</p>
            </div>
        </div>
    </div>
    <div class="col-md-5col">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $totalWarns }}</h3>
                <p class="stat-label">Warnings</p>
            </div>
        </div>
    </div>
    <div class="col-md-5col">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-shoe-prints"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $totalKicks }}</h3>
                <p class="stat-label">Kicks</p>
            </div>
        </div>
    </div>
    <div class="col-md-5col">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-thumbs-up"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $totalCommends }}</h3>
                <p class="stat-label">Commends</p>
            </div>
        </div>
    </div>
    <div class="col-md-5col">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <i class="fas fa-database"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $totalRecords }}</h3>
                <p class="stat-label">Total Records</p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Search all records..." id="allRecordsSearch">
        </div>
    </div>
    <div class="col-md-6">
        <select class="form-select" id="typeFilter">
            <option value="">All Types</option>
            <option value="Ban">Bans</option>
            <option value="Warn">Warnings</option>
            <option value="Kick">Kicks</option>
            <option value="Commend">Commends</option>
        </select>
    </div>
</div>

<!-- All Records Section -->
<div class="section-header">
    <h2>All Records ({{ $totalRecords }})</h2>
</div>

<!-- All Records Table -->
<div class="table-responsive">
    <table class="table table-hover records-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Player ID</th>
                <th>Player Name</th>
                <th>Discord</th>
                <th>Reason/Details</th>
                <th>Date/Time</th>
                <th>Staff Member</th>
                <th>Additional Info</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allRecords as $record)
                <tr data-type="{{ $record['type'] }}">
                    <td>
                        <span class="record-type-badge record-type-{{ $record['type_class'] }}">
                            <i class="{{ $record['icon'] }}"></i>
                            {{ $record['type'] }}
                        </span>
                    </td>
                    <td>
                        <span class="player-id">{{ $record['player_id'] }}</span>
                    </td>
                    <td>
                        <div class="player-info">
                            <div class="player-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ $record['player_name'] }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="discord-tag">{{ $record['discord'] }}</span>
                    </td>
                    <td>
                        <span class="record-reason">{{ $record['reason'] }}</span>
                    </td>
                    <td>
                        <span class="record-datetime">{{ Carbon::parse($record['date_time'])->format('Y-m-d H:i:s') }}</span>
                    </td>
                    <td>
                        <span class="staff-badge">{{ $record['staff_member'] }}</span>
                    </td>
                    <td>
                        <span class="additional-info">{{ $record['additional_info'] }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($allRecords->isEmpty())
    <div class="no-data-message">
        <div class="text-center py-5">
            <i class="fas fa-database fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No records found</h4>
            <p class="text-muted">There are no records to display.</p>
        </div>
    </div>
@endif

<script>
    // Search functionality
    document.getElementById('allRecordsSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.records-table tbody tr');
        
        rows.forEach(row => {
            const playerId = row.cells[1].textContent.toLowerCase();
            const playerName = row.cells[2].textContent.toLowerCase();
            const discord = row.cells[3].textContent.toLowerCase();
            const reason = row.cells[4].textContent.toLowerCase();
            const staff = row.cells[6].textContent.toLowerCase();
            const type = row.cells[0].textContent.toLowerCase();
            
            if (playerId.includes(searchTerm) || playerName.includes(searchTerm) || 
                discord.includes(searchTerm) || reason.includes(searchTerm) || 
                staff.includes(searchTerm) || type.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Type filter functionality
    document.getElementById('typeFilter').addEventListener('change', function(e) {
        const selectedType = e.target.value;
        const rows = document.querySelectorAll('.records-table tbody tr');
        
        rows.forEach(row => {
            const recordType = row.getAttribute('data-type');
            
            if (selectedType === '' || recordType === selectedType) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
