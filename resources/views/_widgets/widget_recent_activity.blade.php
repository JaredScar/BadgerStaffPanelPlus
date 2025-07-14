<?php
// Sample recent activity data - in real implementation this would come from database
$recentActivity = [
    [
        'type' => 'Ban',
        'admin' => 'AdminUser',
        'action' => 'banned',
        'player' => 'PlayerOne',
        'reason' => 'Cheating',
        'time' => '30 minutes ago',
        'badge_class' => 'bg-danger'
    ],
    [
        'type' => 'Warn',
        'admin' => 'ModUser',
        'action' => 'warned',
        'player' => 'PlayerTwo',
        'reason' => 'Inappropriate language',
        'time' => 'about 1 hour ago',
        'badge_class' => 'bg-warning'
    ]
];
?>

<div class="recent-activity-widget h-100">
    <div class="widget-content h-100 p-3">
        <div class="activity-header mb-3">
            <h6 class="widget-subtitle mb-0">Recent Activity</h6>
        </div>
        
        <div class="activity-list-container">
            <div class="activity-list">
                <?php if (!empty($recentActivity)): ?>
                    <?php foreach ($recentActivity as $activity): ?>
                        <div class="activity-item mb-3">
                            <div class="activity-content">
                                <div class="d-flex align-items-start justify-content-between mb-2">
                                    <div class="activity-main">
                                        <span class="badge <?php echo $activity['badge_class']; ?> activity-badge">
                                            <?php echo $activity['type']; ?>
                                        </span>
                                        <span class="activity-time"><?php echo $activity['time']; ?></span>
                                    </div>
                                </div>
                                <div class="activity-details">
                                    <strong><?php echo htmlspecialchars($activity['admin']); ?></strong>
                                    <span class="activity-action"><?php echo $activity['action']; ?></span>
                                    <strong><?php echo htmlspecialchars($activity['player']); ?></strong>
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

<style>
.recent-activity-widget {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
}

.widget-subtitle {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
}

.activity-list-container {
    height: calc(100% - 40px);
    overflow-y: auto;
    padding-right: 5px;
}

.activity-list-container::-webkit-scrollbar {
    width: 4px;
}

.activity-list-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.activity-list-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.activity-list-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.activity-item {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    border-left: 3px solid #fd7e14;
    transition: background-color 0.2s ease;
}

.activity-item:hover {
    background: #e9ecef;
}

.activity-badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.activity-time {
    color: #6c757d;
    font-size: 0.8rem;
    margin-left: 8px;
}

.activity-details {
    color: #2c3e50;
    font-size: 0.9rem;
    margin-bottom: 4px;
}

.activity-action {
    color: #6c757d;
    margin: 0 4px;
}

.activity-reason {
    color: #6c757d;
    font-size: 0.8rem;
}

.bg-danger {
    background-color: #dc3545 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: #000 !important;
}

@media (max-width: 768px) {
    .activity-badge {
        font-size: 0.65rem;
        padding: 3px 6px;
    }
    
    .activity-details {
        font-size: 0.85rem;
    }
    
    .activity-time {
        font-size: 0.75rem;
    }
}
</style> 