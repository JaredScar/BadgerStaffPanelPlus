<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('_partials._html_header')
<body class="background-sizing gta-bg1">
    <div class="container-fluid master-contain">
        @include('_partials._toast')
        @include('_partials._sidebar')
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
                            <button type="button" class="btn btn-success me-2" id="addWidgetBtn">
                                <i class="fas fa-plus me-2"></i>
                                Add Widget
                            </button>
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

    <!-- Add Widget Modal -->
    <div class="modal fade" id="addWidgetModal" tabindex="-1" aria-labelledby="addWidgetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addWidgetModalLabel">
                        <i class="fas fa-plus me-2"></i>
                        Add Widget to Dashboard
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="widget-categories">
                        <!-- General Widgets -->
                        <div class="widget-category mb-4">
                            <h6 class="category-title">
                                <i class="fas fa-layer-group me-2"></i>
                                General Widgets
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="widget-option" data-widget-type="notes" data-widget-category="general">
                                        <div class="widget-option-icon">
                                            <i class="fas fa-sticky-note"></i>
                                        </div>
                                        <div class="widget-option-info">
                                            <h6>Notes</h6>
                                            <p>Quick notes and reminders</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="widget-option" data-widget-type="trust_scores" data-widget-category="general">
                                        <div class="widget-option-icon">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <div class="widget-option-info">
                                            <h6>Trust Scores</h6>
                                            <p>Player trust score overview</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="widget-option" data-widget-type="recent_activity" data-widget-category="general">
                                        <div class="widget-option-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="widget-option-info">
                                            <h6>Recent Activity</h6>
                                            <p>Latest server activities</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Player Widgets -->
                        <div class="widget-category mb-4">
                            <h6 class="category-title">
                                <i class="fas fa-users me-2"></i>
                                Player Widgets
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="widget-option" data-widget-type="players" data-widget-category="players">
                                        <div class="widget-option-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="widget-option-info">
                                            <h6>Players</h6>
                                            <p>Current online players</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="widget-option" data-widget-type="all_players" data-widget-category="players">
                                        <div class="widget-option-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="widget-option-info">
                                            <h6>All Players</h6>
                                            <p>Complete player database</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Records Widgets -->
                        <div class="widget-category mb-4">
                            <h6 class="category-title">
                                <i class="fas fa-clipboard-list me-2"></i>
                                Records Widgets
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="widget-option" data-widget-type="records" data-widget-category="records">
                                        <div class="widget-option-icon">
                                            <i class="fas fa-list"></i>
                                        </div>
                                        <div class="widget-option-info">
                                            <h6>All Records</h6>
                                            <p>Complete records overview</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="widget-option" data-widget-type="bans" data-widget-category="records">
                                        <div class="widget-option-icon">
                                            <i class="fas fa-ban"></i>
                                        </div>
                                        <div class="widget-option-info">
                                            <h6>Bans</h6>
                                            <p>Player ban records</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="widget-option" data-widget-type="warns" data-widget-category="records">
                                        <div class="widget-option-icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="widget-option-info">
                                            <h6>Warnings</h6>
                                            <p>Player warning records</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="widget-option" data-widget-type="kicks" data-widget-category="records">
                                        <div class="widget-option-icon">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                        <div class="widget-option-info">
                                            <h6>Kicks</h6>
                                            <p>Player kick records</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="widget-option" data-widget-type="commends" data-widget-category="records">
                                        <div class="widget-option-icon">
                                            <i class="fas fa-thumbs-up"></i>
                                        </div>
                                        <div class="widget-option-info">
                                            <h6>Commends</h6>
                                            <p>Player commendation records</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="widget-option" data-widget-type="trustscores" data-widget-category="records">
                                        <div class="widget-option-icon">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="widget-option-info">
                                            <h6>Trust Score Records</h6>
                                            <p>Detailed trust score history</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmAddWidget" disabled>
                        <i class="fas fa-plus me-2"></i>
                        Add Selected Widget
                    </button>
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
}

.page-contain {
    height: 100vh;
    overflow-y: auto;
    padding: 0;
}

.content-wrapper {
    padding: 20px;
    min-height: 100vh;
    width: calc(100vw - 295px) !important;
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

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    transform: translateY(-1px);
}

/* Add Widget Modal Styles */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background: linear-gradient(135deg, #fd7e14 0%, #ff9f40 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    border-bottom: none;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.widget-categories {
    max-height: 60vh;
    overflow-y: auto;
}

.widget-category {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 20px;
}

.widget-category:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.category-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #fd7e14;
    display: inline-block;
}

.widget-option {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    height: 100%;
}

.widget-option:hover {
    border-color: #fd7e14;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(253, 126, 20, 0.15);
}

.widget-option.selected {
    border-color: #fd7e14;
    background: rgba(253, 126, 20, 0.05);
    box-shadow: 0 0 0 3px rgba(253, 126, 20, 0.1);
}

.widget-option-icon {
    font-size: 24px;
    color: #fd7e14;
    margin-right: 12px;
    min-width: 40px;
    text-align: center;
}

.widget-option-info h6 {
    margin: 0 0 4px 0;
    font-weight: 600;
    color: #2c3e50;
}

.widget-option-info p {
    margin: 0;
    font-size: 0.9rem;
    color: #6c757d;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
    border-radius: 0 0 12px 12px;
}

/* Blank Widget Styles */
.blank-widget {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 2px dashed #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #6c757d;
    min-height: 200px;
    transition: all 0.3s ease;
}

.blank-widget:hover {
    border-color: #fd7e14;
    background: linear-gradient(135deg, #fff3e0 0%, #ffffff 100%);
    color: #fd7e14;
}

.blank-widget-content {
    padding: 20px;
}

.blank-widget-icon {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.7;
}

.blank-widget h5 {
    margin-bottom: 10px;
    font-weight: 600;
}

.blank-widget p {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.8;
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
    const addWidgetBtn = document.getElementById('addWidgetBtn');
    const normalModeButtons = document.querySelector('.normal-mode-buttons');
    const customizeModeButtons = document.querySelector('.customize-mode-buttons');
    const widgets = document.querySelectorAll('.widget-container');

    let isCustomizeMode = false;
    let selectedWidgetType = null;
    let widgetCounter = 0;

    // Add Widget Modal Elements
    const addWidgetModal = new bootstrap.Modal(document.getElementById('addWidgetModal'));
    const widgetOptions = document.querySelectorAll('.widget-option');
    const confirmAddWidgetBtn = document.getElementById('confirmAddWidget');

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

    // Add Widget functionality
    addWidgetBtn.addEventListener('click', function() {
        selectedWidgetType = null;
        confirmAddWidgetBtn.disabled = true;
        // Reset selections
        widgetOptions.forEach(option => option.classList.remove('selected'));
        addWidgetModal.show();
    });

    // Widget option selection
    widgetOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove previous selection
            widgetOptions.forEach(opt => opt.classList.remove('selected'));
            // Add selection to clicked option
            this.classList.add('selected');
            
            selectedWidgetType = this.getAttribute('data-widget-type');
            confirmAddWidgetBtn.disabled = false;
        });
    });

    // Confirm add widget
    confirmAddWidgetBtn.addEventListener('click', function() {
        if (selectedWidgetType) {
            addBlankWidget(selectedWidgetType);
            addWidgetModal.hide();
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
        
        // Add customize styling to all widgets (including dynamically added ones)
        document.querySelectorAll('.widget-container').forEach(widget => {
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
        
        // Remove customize styling from all widgets (including dynamically added ones)
        document.querySelectorAll('.widget-container').forEach(widget => {
            widget.classList.remove('customize-mode');
        });
        
        // Hide widget headers
        document.querySelectorAll('.widget-header').forEach(header => {
            header.classList.add('d-none');
        });
        
        // Save layout and refresh page
        saveLayoutAndRefresh();
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

    // Add blank widget to grid function
    function addBlankWidget(widgetType) {
        widgetCounter++;
        const widgetId = `${widgetType}_${widgetCounter}`;
        
        // Get widget title and icon
        const widgetInfo = getWidgetInfo(widgetType);
        
        // Find a good position for the new widget (bottom of the grid)
        const existingItems = grid.getGridItems();
        let maxY = 0;
        existingItems.forEach(item => {
            const node = item.gridstackNode;
            maxY = Math.max(maxY, node.y + node.h);
        });
        
        // Create blank widget HTML
        const widgetHTML = `
            <div class="grid-stack-item widget-container ${isCustomizeMode ? 'customize-mode' : ''}" 
                 gs-x="0" gs-y="${maxY}" gs-w="6" gs-h="8" 
                 data-widget-type="${widgetType}"
                 data-widget-id="${widgetId}"
                 data-is-new="true">
                <div class="widget-wrapper h-100">
                    <div class="widget-header ${isCustomizeMode ? '' : 'd-none'}">
                        <div class="widget-title">${widgetInfo.title}</div>
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
                        <div class="blank-widget">
                            <div class="blank-widget-content">
                                <div class="blank-widget-icon">
                                    <i class="${widgetInfo.icon}"></i>
                                </div>
                                <h5>${widgetInfo.title}</h5>
                                <p>This widget will be loaded after saving changes</p>
                                <small class="text-muted">Position this widget where you want it, then click "Done"</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        
        // Add widget to grid
        const newWidget = grid.addWidget(widgetHTML);
        
        // Show a brief notification
        showNotification(`${widgetInfo.title} widget added! Position it where you want and click "Done" to save.`, 'success');
    }

    // Helper function to get widget info
    function getWidgetInfo(widgetType) {
        const widgetInfoMap = {
            'notes': { title: 'Notes', icon: 'fas fa-sticky-note' },
            'trust_scores': { title: 'Trust Scores', icon: 'fas fa-shield-alt' },
            'recent_activity': { title: 'Recent Activity', icon: 'fas fa-clock' },
            'players': { title: 'Players', icon: 'fas fa-user' },
            'all_players': { title: 'All Players', icon: 'fas fa-users' },
            'records': { title: 'All Records', icon: 'fas fa-list' },
            'bans': { title: 'Bans', icon: 'fas fa-ban' },
            'warns': { title: 'Warnings', icon: 'fas fa-exclamation-triangle' },
            'kicks': { title: 'Kicks', icon: 'fas fa-sign-out-alt' },
            'commends': { title: 'Commends', icon: 'fas fa-thumbs-up' },
            'trustscores': { title: 'Trust Score Records', icon: 'fas fa-star' }
        };
        
        return widgetInfoMap[widgetType] || { 
            title: widgetType.charAt(0).toUpperCase() + widgetType.slice(1), 
            icon: 'fas fa-puzzle-piece' 
        };
    }

    // Function to save layout to backend and refresh page
    function saveLayoutAndRefresh() {
        const widgetDataList = [];
        
        grid.getGridItems().forEach(item => {
            const node = item.gridstackNode;
            const widgetType = item.getAttribute('data-widget-type');
            const widgetId = item.getAttribute('data-widget-id');
            
            // Format data to match existing save method expectations
            widgetDataList.push({
                widgetType: mapWidgetTypeForBackend(widgetType),
                widgetId: widgetId,
                x: node.x,
                y: node.y,
                w: node.w,
                h: node.h
            });
        });

        // Show saving indicator
        const savingToast = showNotification('Saving dashboard layout...', 'info', 0);

        // Send layout to existing backend endpoint
        fetch('/verified/dashboard/save', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(widgetDataList)
        })
        .then(response => response.json())
        .then(data => {
            if (data.message && data.message.includes('successfully')) {
                showNotification('Dashboard saved successfully! Refreshing...', 'success');
                // Refresh page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification('Error saving dashboard: ' + (data.message || 'Unknown error'), 'danger');
                // Re-enable customize mode on error
                isCustomizeMode = true;
                normalModeButtons.classList.add('d-none');
                customizeModeButtons.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Error saving layout:', error);
            showNotification('Error saving dashboard layout. Please try again.', 'danger');
            // Re-enable customize mode on error
            isCustomizeMode = true;
            normalModeButtons.classList.add('d-none');
            customizeModeButtons.classList.remove('d-none');
        });
    }

    // Helper function to map widget types for backend compatibility
    function mapWidgetTypeForBackend(widgetType) {
        const widgetMap = {
            'notes': 'widget_notes',
            'trust_scores': 'widget_trust_scores', 
            'recent_activity': 'widget_recent_activity',
            'players': 'players.widget_players',
            'all_players': 'players.widget_all_players',
            'records': 'records.widget_records',
            'bans': 'records.widget_bans',
            'warns': 'records.widget_warns',
            'kicks': 'records.widget_kicks',
            'commends': 'records.widget_commends',
            'trustscores': 'records.widget_trustscores'
        };
        
        return widgetMap[widgetType] || widgetType;
    }

    // Helper function to show notifications
    function showNotification(message, type = 'info', duration = 3000) {
        const toastContainer = document.querySelector('.toast-container') || createToastContainer();
        
        const toastId = 'toast-' + Date.now();
        const toastHTML = `
            <div class="toast align-items-center text-white bg-${type}" role="alert" id="${toastId}">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>`;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: duration });
        toast.show();
        
        // Remove toast element after it's hidden
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
        
        return toast;
    }

    // Create toast container if it doesn't exist
    function createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
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
