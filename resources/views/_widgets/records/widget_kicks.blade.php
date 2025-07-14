<?php
use App\Models\Kick;
use Carbon\Carbon;

if (isset($data['selected_pid']))
    $kickData = Kick::with('getPlayer')->with('getStaff')->where('player_id', $data['selected_pid'])->get();
else
    $kickData = Kick::with('getPlayer')->with('getStaff')->get();

$data['data'] = $kickData;

// Calculate statistics
$todaysKicks = $kickData->filter(function($kick) {
    return Carbon::parse($kick->created_at)->isToday();
})->count();

$thisWeekKicks = $kickData->filter(function($kick) {
    return Carbon::parse($kick->created_at)->isCurrentWeek();
})->count();

// Find most active staff today
$staffToday = $kickData->filter(function($kick) {
    return Carbon::parse($kick->created_at)->isToday();
})->groupBy('staff_id');

$mostActiveStaff = '';
$mostActiveCount = 0;
foreach ($staffToday as $staffId => $staffKicks) {
    if ($staffKicks->count() > $mostActiveCount) {
        $mostActiveCount = $staffKicks->count();
        $mostActiveStaff = $staffKicks->first()->getStaff->staff_username ?? 'Unknown';
    }
}

if (!$mostActiveStaff) {
    $mostActiveStaff = 'No activity today';
    $mostActiveCount = 0;
}
?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $todaysKicks }}</h3>
                <p class="stat-label">Today's Kicks</p>
                <small class="text-muted">in the last 24 hours</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $thisWeekKicks }}</h3>
                <p class="stat-label">This Week</p>
                <small class="text-muted">total kicks</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <i class="fas fa-filter"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number text-success">{{ $mostActiveStaff }}</h3>
                <p class="stat-label">Most Active Staff</p>
                <small class="text-muted">{{ $mostActiveCount }} kicks today</small>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Search kicks..." id="kickSearch">
        </div>
    </div>
    <div class="col-md-6">
        <select class="form-select" id="staffFilter">
            <option value="">All Staff</option>
            @foreach($kickData->pluck('getStaff.staff_username')->unique()->filter() as $staff)
                <option value="{{ $staff }}">{{ $staff }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Kick Records Section -->
<div class="section-header">
    <h2>Kick Records ({{ $kickData->count() }})</h2>
</div>

<!-- Kick Records Table -->
<div class="table-responsive">
    <table class="table table-hover kicks-table">
        <thead>
            <tr>
                <th>Player ID</th>
                <th>Player Name</th>
                <th>Discord</th>
                <th>Reason</th>
                <th>Date/Time</th>
                <th>Staff Member</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data["data"] as $kick)
                <tr>
                    <td>
                        <span class="player-id">{{ $kick->player_id }}</span>
                    </td>
                    <td>
                        <div class="player-info">
                            <div class="player-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ $kick->getPlayer->last_player_name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="discord-tag">{{ $kick->getPlayer->discord_id ?? 'Not Available' }}</span>
                    </td>
                    <td>
                        <span class="kick-reason">{{ $kick->reason }}</span>
                    </td>
                    <td>
                        <span class="kick-datetime">{{ Carbon::parse($kick->created_at)->format('Y-m-d H:i:s') }}</span>
                    </td>
                    <td>
                        <span class="staff-badge">{{ $kick->getStaff->staff_username ?? 'Unknown' }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($kickData->isEmpty())
    <div class="no-data-message">
        <div class="text-center py-5">
            <i class="fas fa-user-times fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No kick records found</h4>
            <p class="text-muted">There are no kick records to display.</p>
        </div>
    </div>
@endif

<script>
    // Search functionality
    document.getElementById('kickSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.kicks-table tbody tr');
        
        rows.forEach(row => {
            const playerId = row.cells[0].textContent.toLowerCase();
            const playerName = row.cells[1].textContent.toLowerCase();
            const discord = row.cells[2].textContent.toLowerCase();
            const reason = row.cells[3].textContent.toLowerCase();
            const staff = row.cells[5].textContent.toLowerCase();
            
            if (playerId.includes(searchTerm) || playerName.includes(searchTerm) || 
                discord.includes(searchTerm) || reason.includes(searchTerm) || staff.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Staff filter functionality
    document.getElementById('staffFilter').addEventListener('change', function(e) {
        const selectedStaff = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.kicks-table tbody tr');
        
        rows.forEach(row => {
            const staffCell = row.cells[5].textContent.toLowerCase();
            
            if (selectedStaff === '' || staffCell.includes(selectedStaff)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
