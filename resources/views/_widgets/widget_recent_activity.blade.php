<?php
use App\Support\PanelActivity;
use Illuminate\Support\Facades\Session;

$recentActivity = PanelActivity::recent(Session::get('server_id') ? (int) Session::get('server_id') : null, null, 12);
?>

<div class="recent-activity-widget h-100">
    <div class="widget-content h-100 p-3">
        <div class="activity-header mb-3">
            <h6 class="widget-subtitle mb-0">Recent Activity</h6>
        </div>

        <div class="activity-list-container">
            <div class="activity-list">
                <?php if ($recentActivity->isNotEmpty()): ?>
                    <?php foreach ($recentActivity as $activity): ?>
                        <div class="activity-item mb-3">
                            <div class="activity-content">
                                <div class="d-flex align-items-start justify-content-between mb-2">
                                    <div class="activity-main">
                                        <span class="badge <?php echo $activity['badge']; ?> activity-badge">
                                            <?php echo $activity['type']; ?>
                                        </span>
                                        <span class="activity-time"><?php echo $activity['time']; ?></span>
                                    </div>
                                </div>
                                <div class="activity-details">
                                    <strong><?php echo htmlspecialchars($activity['admin']); ?></strong>
                                    <span class="activity-action"><?php echo $activity['verb']; ?></span>
                                    <a href="/web/verified/players/<?php echo (int) $activity['player_id']; ?>">
                                        <strong><?php echo htmlspecialchars($activity['player']); ?></strong>
                                    </a>
                                </div>
                                <div class="activity-reason">
                                    <small>Reason: <?php echo htmlspecialchars($activity['reason']); ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-history fa-2x mb-2"></i>
                        <p>No recent activity</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
