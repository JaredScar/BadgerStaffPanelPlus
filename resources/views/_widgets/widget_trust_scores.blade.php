<?php
// Sample trust score data - in real implementation this would come from database
$averageTrustScore = 87;
$weeklyChange = 5; // +5% this week
$trendDirection = $weeklyChange > 0 ? 'up' : 'down';
$trendColor = $weeklyChange > 0 ? 'success' : 'danger';
?>

<div class="trust-scores-widget h-100">
    <div class="widget-content h-100 p-3">
        <div class="trust-score-display text-center">
            <div class="mb-3">
                <span class="trust-score-label">Average Trust Score</span>
                <i class="fas fa-chart-line ms-2 text-muted"></i>
            </div>
            
            <div class="trust-score-value mb-3">
                <?php echo $averageTrustScore; ?>
            </div>
            
            <div class="trust-score-trend">
                <span class="trend-indicator text-<?php echo $trendColor; ?>">
                    <i class="fas fa-arrow-<?php echo $trendDirection; ?> me-1"></i>
                    +<?php echo abs($weeklyChange); ?>%
                </span>
                <span class="trend-period text-muted">This week</span>
            </div>
        </div>
    </div>
</div>

<style>
.trust-scores-widget {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
}

.trust-score-display {
    width: 100%;
}

.trust-score-label {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
}

.trust-score-value {
    font-size: 3.5rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1;
}

.trust-score-trend {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.trend-indicator {
    font-weight: 600;
    font-size: 0.9rem;
}

.trend-period {
    font-size: 0.85rem;
}

.text-success {
    color: #28a745 !important;
}

.text-danger {
    color: #dc3545 !important;
}

.text-muted {
    color: #6c757d !important;
}

@media (max-width: 768px) {
    .trust-score-value {
        font-size: 2.5rem;
    }
    
    .trust-score-label {
        font-size: 0.8rem;
    }
}
</style> 