<?php
use App\Models\Warn;
use Carbon\Carbon;

if (isset($data['selected_pid']))
    $warnData = Warn::with('getPlayer')->with('getStaff')->where('player_id', $data['selected_pid'])->get();
else
    $warnData = Warn::with('getPlayer')->with('getStaff')->get();

// Calculate statistics
$highSeverityCount = $warnData->where('severity', 'High')->count();
$mediumSeverityCount = $warnData->where('severity', 'Medium')->count();
$lowSeverityCount = $warnData->where('severity', 'Low')->count();
$totalWarnings = $warnData->count();

$data['data'] = $warnData;
?>

<div class="warns-container">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card stats-card-danger">
                <div class="stats-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $highSeverityCount }}</h3>
                    <p>High Severity</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-card-warning">
                <div class="stats-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $mediumSeverityCount }}</h3>
                    <p>Medium Severity</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-card-success">
                <div class="stats-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $lowSeverityCount }}</h3>
                    <p>Low Severity</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card stats-card-info">
                <div class="stats-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $totalWarnings }}</h3>
                    <p>Total Warnings</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Controls -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="warningsSearch" placeholder="Search warnings..." class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <select id="severityFilter" class="form-select">
                <option value="">All Severities</option>
                <option value="High">High Severity</option>
                <option value="Medium">Medium Severity</option>
                <option value="Low">Low Severity</option>
            </select>
        </div>
    </div>

    <!-- Warnings Table -->
    <div class="table-container">
        <div class="table-header">
            <h5><i class="fas fa-exclamation-triangle text-warning me-2"></i>Warning Records ({{ $totalWarnings }})</h5>
        </div>
        <div class="table-responsive">
            <table id="warningsTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>Player ID</th>
                        <th>Player Name</th>
                        <th>Discord</th>
                        <th>Reason</th>
                        <th>Severity</th>
                        <th>Date/Time</th>
                        <th>Staff Member</th>
                    </tr>
                </thead>
                <tbody>
                    @if($warnData->count() > 0)
                        @foreach ($warnData as $warn)
                            <tr>
                                <td>
                                    <span class="player-id">{{ $warn->player_id }}</span>
                                </td>
                                <td>
                                    <div class="player-info">
                                        <div class="player-avatar">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <span class="player-name">{{ $warn->getPlayer->last_player_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="discord-tag">{{ $warn->getPlayer->discord_id }}</span>
                                </td>
                                <td>
                                    <span class="warn-reason">{{ $warn->reason }}</span>
                                </td>
                                <td>
                                    <span class="severity-badge severity-{{ strtolower($warn->severity ?? 'medium') }}">
                                        {{ $warn->severity ?? 'Medium' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="datetime-badge">{{ Carbon::parse($warn->created_at)->format('Y-m-d H:i:s') }}</span>
                                </td>
                                <td>
                                    <span class="staff-badge">{{ $warn->getStaff->staff_username }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    <p>No warnings found</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Warning Modal -->
<div class="modal fade" id="addWarningModal" tabindex="-1" aria-labelledby="addWarningModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addWarningModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Add Warning
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addWarningForm">
                    <div class="mb-3">
                        <label for="warningPlayerId" class="form-label">Player ID</label>
                        <input type="text" class="form-control" id="warningPlayerId" required>
                    </div>
                    <div class="mb-3">
                        <label for="warningPlayerName" class="form-label">Player Name</label>
                        <input type="text" class="form-control" id="warningPlayerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="warningDiscord" class="form-label">Discord Tag</label>
                        <input type="text" class="form-control" id="warningDiscord" placeholder="username#1234">
                    </div>
                    <div class="mb-3">
                        <label for="warningReason" class="form-label">Warning Reason</label>
                        <textarea class="form-control" id="warningReason" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="warningSeverity" class="form-label">Severity</label>
                        <select class="form-select" id="warningSeverity" required>
                            <option value="">Select Severity</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="warningStaff" class="form-label">Staff Member</label>
                        <select class="form-select" id="warningStaff" required>
                            <option value="">Select Staff Member</option>
                            <option value="AdminUser">AdminUser</option>
                            <option value="ModUser">ModUser</option>
                            <option value="HelperUser">HelperUser</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="addWarning()">Add Warning</button>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('warningsSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#warningsTable tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Severity filter
document.getElementById('severityFilter').addEventListener('change', function() {
    const selectedSeverity = this.value;
    const tableRows = document.querySelectorAll('#warningsTable tbody tr');
    
    tableRows.forEach(row => {
        const severityCell = row.querySelector('.severity-badge');
        if (!selectedSeverity || (severityCell && severityCell.textContent.trim() === selectedSeverity)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Add warning function
function addWarning() {
    const form = document.getElementById('addWarningForm');
    const formData = new FormData(form);
    
    // Here you would typically send the data to your backend
    console.log('Adding warning:', {
        playerId: document.getElementById('warningPlayerId').value,
        playerName: document.getElementById('warningPlayerName').value,
        discord: document.getElementById('warningDiscord').value,
        reason: document.getElementById('warningReason').value,
        severity: document.getElementById('warningSeverity').value,
        staff: document.getElementById('warningStaff').value
    });
    
    // Close modal and reset form
    const modal = bootstrap.Modal.getInstance(document.getElementById('addWarningModal'));
    modal.hide();
    form.reset();
    
    // Show success message
    alert('Warning added successfully!');
}
</script>
