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

// Add trust score updates (simulated for now)
$trustUpdates = 1; // This would come from a TrustScore model if it exists

?>

<div class="all-records-container">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-5col">
            <div class="stats-card stats-card-danger">
                <div class="stats-icon">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $totalBans }}</h3>
                    <p>Bans</p>
                </div>
            </div>
        </div>
        <div class="col-md-5col">
            <div class="stats-card stats-card-warning">
                <div class="stats-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $totalWarns }}</h3>
                    <p>Warnings</p>
                </div>
            </div>
        </div>
        <div class="col-md-5col">
            <div class="stats-card stats-card-info">
                <div class="stats-icon">
                    <i class="fas fa-shoe-prints"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $totalKicks }}</h3>
                    <p>Kicks</p>
                </div>
            </div>
        </div>
        <div class="col-md-5col">
            <div class="stats-card stats-card-success">
                <div class="stats-icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $totalCommends }}</h3>
                    <p>Commends</p>
                </div>
            </div>
        </div>
        <div class="col-md-5col">
            <div class="stats-card stats-card-primary">
                <div class="stats-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $trustUpdates }}</h3>
                    <p>Trust Updates</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Controls -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="allRecordsSearch" placeholder="Search all records..." class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <select id="typeFilter" class="form-select">
                <option value="">All Types</option>
                <option value="Ban">Bans</option>
                <option value="Warn">Warnings</option>
                <option value="Kick">Kicks</option>
                <option value="Commend">Commends</option>
            </select>
        </div>
    </div>

    <!-- All Records Table -->
    <div class="table-container">
        <div class="table-header">
            <h5><i class="fas fa-database text-warning me-2"></i>All Records ({{ $totalRecords }})</h5>
        </div>
        <div class="table-responsive">
            <table id="allRecordsTable" class="table table-hover">
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
                    @if($allRecords->count() > 0)
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
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <span class="player-name">{{ $record['player_name'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="discord-tag">{{ $record['discord'] }}</span>
                                </td>
                                <td>
                                    <span class="record-reason">{{ $record['reason'] }}</span>
                                </td>
                                <td>
                                    <span class="datetime-badge">{{ Carbon::parse($record['date_time'])->format('Y-m-d H:i:s') }}</span>
                                </td>
                                <td>
                                    <span class="staff-badge">{{ $record['staff_member'] }}</span>
                                </td>
                                <td>
                                    <span class="additional-info">{{ $record['additional_info'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-database text-warning"></i>
                                    <p>No records found</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('allRecordsSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#allRecordsTable tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Type filter
document.getElementById('typeFilter').addEventListener('change', function() {
    const selectedType = this.value;
    const tableRows = document.querySelectorAll('#allRecordsTable tbody tr');
    
    tableRows.forEach(row => {
        const recordType = row.getAttribute('data-type');
        if (!selectedType || recordType === selectedType) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
