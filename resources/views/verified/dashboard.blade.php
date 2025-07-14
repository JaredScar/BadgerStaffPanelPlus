<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials._html_header')
<body class="background-sizing gta-bg1">
    <div class="container-fluid master-contain">
        @include('_partials._toast')
        <div class="row">
            <div class="col col-auto px-0">
                @include('_partials._sidebar')
            </div>
            <div class="col col-auto flex-fill page-contain">
                <div class="content-wrapper">
                    <!-- Dashboard Header -->
                    <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="dashboard-title">Dashboard</h1>
                            <p class="dashboard-subtitle">Welcome back! Here's what's happening with your server.</p>
                        </div>
                        <div class="header-actions">
                            <div class="dashboard-selector me-3">
                                <select class="form-select" id="dashboardSelect">
                                    <option value="main" selected>Main Dashboard</option>
                                    <option value="analytics">Analytics Dashboard</option>
                                    <option value="staff">Staff Dashboard</option>
                                </select>
                            </div>
                            <div class="action-buttons">
                                <!-- Normal Mode Buttons -->
                                <div class="normal-mode-buttons">
                                    <button type="button" class="btn btn-outline-secondary" id="customizeBtn">
                                        <i class="fas fa-cog me-2"></i>
                                        Customize
                                    </button>
                                </div>
                                <!-- Customize Mode Buttons -->
                                <div class="customize-mode-buttons d-none">
                                    <button type="button" class="btn btn-outline-secondary me-2" id="resetLayoutBtn">
                                        <i class="fas fa-undo me-2"></i>
                                        Reset Layout
                                    </button>
                                    <button type="button" class="btn btn-primary" id="doneBtn">
                                        <i class="fas fa-check me-2"></i>
                                        Done
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dashboard Content -->
                    <div class="dashboard-content">
                        <div class="grid-stack" id="dashboard-grid">
                            <!-- Notes Widget -->
                            <div class="grid-stack-item widget-container" 
                                 gs-x="0" gs-y="0" gs-w="6" gs-h="8" 
                                 data-widget-type="notes">
                                <div class="widget-wrapper h-100">
                                    <div class="widget-header d-none">
                                        <div class="widget-title">Notes</div>
                                        <div class="widget-controls">
                                            <button class="widget-control-btn" data-action="expand">
                                                <i class="fas fa-expand"></i>
                                            </button>
                                            <button class="widget-control-btn" data-action="drag">
                                                <i class="fas fa-grip-vertical"></i>
                                            </button>
                                            <button class="widget-control-btn" data-action="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="widget-content">
                                        @include('_widgets.widget_notes')
                                    </div>
                                </div>
                            </div>

                            <!-- Trust Scores Widget -->
                            <div class="grid-stack-item widget-container" 
                                 gs-x="6" gs-y="0" gs-w="6" gs-h="8" 
                                 data-widget-type="trust_scores">
                                <div class="widget-wrapper h-100">
                                    <div class="widget-header d-none">
                                        <div class="widget-title">Trust Scores</div>
                                        <div class="widget-controls">
                                            <button class="widget-control-btn" data-action="expand">
                                                <i class="fas fa-expand"></i>
                                            </button>
                                            <button class="widget-control-btn" data-action="drag">
                                                <i class="fas fa-grip-vertical"></i>
                                            </button>
                                            <button class="widget-control-btn" data-action="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="widget-content">
                                        @include('_widgets.widget_trust_scores')
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activity Widget -->
                            <div class="grid-stack-item widget-container" 
                                 gs-x="0" gs-y="8" gs-w="12" gs-h="10" 
                                 data-widget-type="recent_activity">
                                <div class="widget-wrapper h-100">
                                    <div class="widget-header d-none">
                                        <div class="widget-title">Recent Activity</div>
                                        <div class="widget-controls">
                                            <button class="widget-control-btn" data-action="expand">
                                                <i class="fas fa-expand"></i>
                                            </button>
                                            <button class="widget-control-btn" data-action="drag">
                                                <i class="fas fa-grip-vertical"></i>
                                            </button>
                                            <button class="widget-control-btn" data-action="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="widget-content">
                                        @include('_widgets.widget_recent_activity')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('_partials._html_footer')
</body>
</html>

<style>
/* Dashboard specific styles */
.master-contain {
    padding: 0;
    margin: 0;
    height: 100vh;
    overflow: hidden;
}

.page-contain {
    height: 100vh;
    overflow-y: auto;
    padding: 0;
}

.content-wrapper {
    padding: 20px;
    min-height: 100vh;
}

.dashboard-header {
    margin-bottom: 30px;
}

.dashboard-title {
    font-size: 32px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.dashboard-subtitle {
    color: #6c757d;
    margin: 0;
    font-size: 16px;
}

.header-actions {
    display: flex;
    align-items: center;
}

.dashboard-selector .form-select {
    border-radius: 8px;
    border: 2px solid #e3e6f0;
    font-weight: 500;
    min-width: 180px;
}

.dashboard-selector .form-select:focus {
    border-color: #fd7e14;
    box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #fd7e14 0%, #ff9f40 100%);
    border: none;
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #e96b00 0%, #fd7e14 100%);
    transform: translateY(-1px);
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
    background: transparent;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-1px);
}

/* Widget System */
.grid-stack {
    background: transparent;
}

.widget-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.widget-container.customize-mode {
    border-color: #fd7e14;
    box-shadow: 0 4px 20px rgba(253, 126, 20, 0.15);
}

.widget-wrapper {
    border-radius: 12px;
    overflow: hidden;
}

.widget-header {
    background: linear-gradient(135deg, #fd7e14 0%, #ff9f40 100%);
    color: white;
    padding: 8px 12px;
    display: flex;
    justify-content: between;
    align-items: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.widget-title {
    flex-grow: 1;
}

.widget-controls {
    display: flex;
    gap: 8px;
}

.widget-control-btn {
    background: none;
    border: none;
    color: white;
    padding: 4px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.8rem;
}

.widget-control-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.widget-content {
    height: 100%;
    background: white;
    border-radius: 0 0 12px 12px;
}

/* Customize mode styles */
.customize-mode .widget-header {
    display: flex !important;
}

/* Grid Stack Overrides */
.grid-stack-item {
    transition: all 0.3s ease;
}

.grid-stack-item-content {
    background: transparent;
    border-radius: 12px;
}

.grid-stack-item.ui-draggable-dragging {
    transform: rotate(5deg);
    z-index: 1000;
}

/* Responsive */
@media (max-width: 1200px) {
    .header-actions {
        flex-direction: column;
        gap: 10px;
    }
    
    .dashboard-selector {
        margin-right: 0 !important;
        margin-bottom: 10px;
    }
}

@media (max-width: 768px) {
    .dashboard-title {
        font-size: 24px;
    }
    
    .dashboard-subtitle {
        font-size: 14px;
    }
    
    .content-wrapper {
        padding: 15px;
    }
    
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .header-actions {
        width: 100%;
        flex-direction: column;
    }
    
    .dashboard-selector .form-select {
        min-width: 100%;
    }
    
    .action-buttons {
        width: 100%;
    }
    
    .action-buttons .btn {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .customize-mode-buttons {
        display: flex !important;
        gap: 10px;
    }
    
    .customize-mode-buttons .btn {
        flex: 1;
        margin-bottom: 0 !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize GridStack
    const grid = GridStack.init({
        cellHeight: 50,
        verticalMargin: 10,
        horizontalMargin: 10,
        animate: true,
        float: false,
        disableDrag: true,
        disableResize: true,
        acceptWidgets: true
    });

    // UI Elements
    const customizeBtn = document.getElementById('customizeBtn');
    const doneBtn = document.getElementById('doneBtn');
    const resetLayoutBtn = document.getElementById('resetLayoutBtn');
    const normalModeButtons = document.querySelector('.normal-mode-buttons');
    const customizeModeButtons = document.querySelector('.customize-mode-buttons');
    const widgets = document.querySelectorAll('.widget-container');

    let isCustomizeMode = false;

    // Customize Mode Toggle
    customizeBtn.addEventListener('click', function() {
        enableCustomizeMode();
    });

    doneBtn.addEventListener('click', function() {
        disableCustomizeMode();
    });

    resetLayoutBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the layout to default?')) {
            resetLayout();
        }
    });

    function enableCustomizeMode() {
        isCustomizeMode = true;
        
        // Toggle UI
        normalModeButtons.classList.add('d-none');
        customizeModeButtons.classList.remove('d-none');
        
        // Enable grid interactions
        grid.enableMove(true);
        grid.enableResize(true);
        
        // Add customize styling to widgets
        widgets.forEach(widget => {
            widget.classList.add('customize-mode');
        });
        
        // Show widget headers
        document.querySelectorAll('.widget-header').forEach(header => {
            header.classList.remove('d-none');
        });
    }

    function disableCustomizeMode() {
        isCustomizeMode = false;
        
        // Toggle UI
        normalModeButtons.classList.remove('d-none');
        customizeModeButtons.classList.add('d-none');
        
        // Disable grid interactions
        grid.enableMove(false);
        grid.enableResize(false);
        
        // Remove customize styling from widgets
        widgets.forEach(widget => {
            widget.classList.remove('customize-mode');
        });
        
        // Hide widget headers
        document.querySelectorAll('.widget-header').forEach(header => {
            header.classList.add('d-none');
        });
        
        // Save layout (in real implementation, this would save to database)
        saveLayout();
    }

    function resetLayout() {
        // Reset to default positions
        const defaultLayout = [
            { x: 0, y: 0, w: 6, h: 8, id: 'notes' },
            { x: 6, y: 0, w: 6, h: 8, id: 'trust_scores' },
            { x: 0, y: 8, w: 12, h: 10, id: 'recent_activity' }
        ];
        
        // Apply default layout
        grid.load(defaultLayout.map(item => ({
            ...item,
            content: document.querySelector(`[data-widget-type="${item.id}"]`).outerHTML
        })));
    }

    function saveLayout() {
        const layout = [];
        grid.getGridItems().forEach(item => {
            const node = item.gridstackNode;
            const widgetType = item.getAttribute('data-widget-type');
            layout.push({
                x: node.x,
                y: node.y,
                w: node.w,
                h: node.h,
                type: widgetType
            });
        });
        
        // In real implementation, send to server
        console.log('Saving layout:', layout);
    }

    // Widget control handlers
    document.addEventListener('click', function(e) {
        if (e.target.closest('.widget-control-btn')) {
            const btn = e.target.closest('.widget-control-btn');
            const action = btn.getAttribute('data-action');
            const widget = btn.closest('.widget-container');
            
            switch(action) {
                case 'expand':
                    // Toggle widget size
                    const currentW = parseInt(widget.getAttribute('gs-w'));
                    const newW = currentW === 12 ? 6 : 12;
                    grid.update(widget, { w: newW });
                    break;
                    
                case 'remove':
                    if (confirm('Are you sure you want to remove this widget?')) {
                        grid.removeWidget(widget);
                    }
                    break;
                    
                case 'drag':
                    // Drag functionality is handled by GridStack
                    break;
            }
        }
    });

    // Dashboard selector
    document.getElementById('dashboardSelect').addEventListener('change', function() {
        const selectedDashboard = this.value;
        console.log('Switching to dashboard:', selectedDashboard);
        // In real implementation, this would load different dashboard layouts
    });
});
</script>
