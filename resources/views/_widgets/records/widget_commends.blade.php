<?php
use App\Models\Commend;
use Carbon\Carbon;

if (isset($data['selected_pid']))
    $tabData = Commend::with('getPlayer')->with('getStaff')->where('player_id', $data['selected_pid'])->get();
else
    $tabData = Commend::with('getPlayer')->with('getStaff')->get();

// Calculate statistics
$today = Carbon::today();
$thisWeek = Carbon::now()->startOfWeek();
$thisMonth = Carbon::now()->startOfMonth();

$todayCommends = collect($tabData)->filter(function ($commend) use ($today) {
    return Carbon::parse($commend->date)->isToday();
})->count();

$weekCommends = collect($tabData)->filter(function ($commend) use ($thisWeek) {
    return Carbon::parse($commend->date)->greaterThanOrEqualTo($thisWeek);
})->count();

$monthCommends = collect($tabData)->filter(function ($commend) use ($thisMonth) {
    return Carbon::parse($commend->date)->greaterThanOrEqualTo($thisMonth);
})->count();

// Find most active staff member (who gives the most commends)
$staffCommends = collect($tabData)->groupBy('staff_id');
$mostActiveStaff = $staffCommends->map(function ($commends, $staffId) {
    $staff = $commends->first()->getStaff ?? null;
    return [
        'name' => $staff ? $staff->name : 'Unknown',
        'count' => $commends->count()
    ];
})->sortByDesc('count')->first();

$data['data'] = $tabData;
?>

<div class="container-fluid">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card commends-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">Today's Commends</h5>
                            <h2 class="commends-stat-number"><?php echo $todayCommends; ?></h2>
                        </div>
                        <div class="commends-stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card commends-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">This Week</h5>
                            <h2 class="commends-stat-number"><?php echo $weekCommends; ?></h2>
                        </div>
                        <div class="commends-stat-icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card commends-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">This Month</h5>
                            <h2 class="commends-stat-number"><?php echo $monthCommends; ?></h2>
                        </div>
                        <div class="commends-stat-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card commends-stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-muted mb-0">Most Active Staff</h5>
                            <h2 class="commends-stat-number"><?php echo $mostActiveStaff ? $mostActiveStaff['name'] : 'N/A'; ?></h2>
                            <small class="text-muted"><?php echo $mostActiveStaff ? $mostActiveStaff['count'] . ' commends' : ''; ?></small>
                        </div>
                        <div class="commends-stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" class="form-control" id="commendsSearch" placeholder="Search commends...">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="commendsStaffFilter">
                <option value="">All Staff</option>
                <option value="AdminUser">AdminUser</option>
                <option value="ModUser">ModUser</option>
                <option value="HelperUser">HelperUser</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="commendsTypeFilter">
                <option value="">All Types</option>
                <option value="Excellent Roleplay">Excellent Roleplay</option>
                <option value="Helpful Behavior">Helpful Behavior</option>
                <option value="Good Sportsmanship">Good Sportsmanship</option>
                <option value="Community Contribution">Community Contribution</option>
                <option value="Leadership">Leadership</option>
                <option value="Other">Other</option>
            </select>
        </div>
    </div>

    <!-- Commends Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="commendsTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Player</th>
                                    <th>Type</th>
                                    <th>Reason</th>
                                    <th>Date/Time</th>
                                    <th>Staff Member</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($tabData)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No commends found</h5>
                                                <p class="text-muted">Start by adding your first commend record</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($tabData as $commend): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="player-avatar me-3">
                                                        <img src="https://via.placeholder.com/40x40?text=<?php echo substr($commend->getPlayer->name ?? 'P', 0, 1); ?>" 
                                                             alt="Player Avatar" class="rounded-circle">
                                                    </div>
                                                    <div class="player-info">
                                                        <div class="player-name"><?php echo $commend->getPlayer->name ?? 'Unknown'; ?></div>
                                                        <div class="player-id">ID: <?php echo $commend->player_id; ?></div>
                                                        <?php if ($commend->getPlayer->discord_id): ?>
                                                            <div class="player-discord">
                                                                <i class="fab fa-discord"></i> 
                                                                <?php echo $commend->getPlayer->discord_id; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge commend-type-badge">
                                                    <?php echo $commend->type ?? 'General'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="commend-reason">
                                                    <?php echo $commend->reason ?? 'No reason provided'; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="commend-datetime">
                                                    <div class="date"><?php echo Carbon::parse($commend->date)->format('M d, Y'); ?></div>
                                                    <div class="time"><?php echo Carbon::parse($commend->date)->format('H:i'); ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="staff-info">
                                                    <span class="badge staff-badge">
                                                        <i class="fas fa-user-shield"></i>
                                                        <?php echo $commend->getStaff->name ?? 'Unknown'; ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Commends-specific styles */
.commends-stat-card {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.commends-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.commends-stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #28a745;
    margin-bottom: 0;
}

.commends-stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.commend-type-badge {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.commend-reason {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #666;
}

.commend-datetime {
    font-size: 0.9rem;
}

.commend-datetime .date {
    font-weight: 500;
    color: #333;
}

.commend-datetime .time {
    color: #666;
    font-size: 0.8rem;
}

.staff-badge {
    background: linear-gradient(135deg, #fd7e14 0%, #fd9843 100%);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.player-avatar img {
    width: 40px;
    height: 40px;
    object-fit: cover;
}

.player-info {
    line-height: 1.2;
}

.player-name {
    font-weight: 600;
    color: #333;
}

.player-id {
    font-size: 0.8rem;
    color: #666;
}

.player-discord {
    font-size: 0.8rem;
    color: #5865f2;
}

.empty-state {
    padding: 3rem 1rem;
}

#commendsTable tbody tr {
    transition: background-color 0.3s ease;
}

#commendsTable tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.1);
}

.btn-outline-primary:hover {
    background-color: #fd7e14;
    border-color: #fd7e14;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('commendsSearch');
    const staffFilter = document.getElementById('commendsStaffFilter');
    const typeFilter = document.getElementById('commendsTypeFilter');
    const table = document.getElementById('commendsTable');
    const tbody = table.getElementsByTagName('tbody')[0];
    const rows = Array.from(tbody.getElementsByTagName('tr'));

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const staffValue = staffFilter.value;
        const typeValue = typeFilter.value;

        rows.forEach(row => {
            if (row.cells.length === 1) return; // Skip empty state row
            
            const playerName = row.cells[0].textContent.toLowerCase();
            const type = row.cells[1].textContent.trim();
            const reason = row.cells[2].textContent.toLowerCase();
            const staff = row.cells[4].textContent.trim();
            
            const matchesSearch = playerName.includes(searchTerm) || 
                                reason.includes(searchTerm) ||
                                type.toLowerCase().includes(searchTerm);
            const matchesStaff = !staffValue || staff.includes(staffValue);
            const matchesType = !typeValue || type.includes(typeValue);
            
            if (matchesSearch && matchesStaff && matchesType) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    staffFilter.addEventListener('change', filterTable);
    typeFilter.addEventListener('change', filterTable);
});
</script>
