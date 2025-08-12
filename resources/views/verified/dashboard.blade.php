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
                    <h1 class="dashboard-title">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </h1>
                    <p class="dashboard-subtitle">Welcome back! Here's what's happening with your server.</p>
                </div>
                <div class="header-actions">
                    <div class="dashboard-selector me-3">
                        <div class="input-group">
                            <select class="form-select" id="dashboardSelect">
                                @foreach($data['available_dashboards'] as $dashboard)
                                    <option value="{{ $dashboard }}" {{ $dashboard === $data['current_dashboard'] ? 'selected' : '' }}>
                                        {{ ucfirst($dashboard) }} Dashboard
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" id="createDashboardBtn" title="Create New Dashboard">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        @if($data['current_dashboard'] !== 'main')
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="deleteDashboardBtn">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        @endif
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
                    @if(isset($data['layout']) && count($data['layout']) > 0)
                        @foreach($data['layout'] as $widget)
                            <div class="grid-stack-item widget-container" 
                                 gs-x="{{ $widget->col }}" gs-y="{{ $widget->row }}" gs-w="{{ $widget->size_x }}" gs-h="{{ $widget->size_y }}" 
                                 data-widget-type="{{ $widget->widget_type }}"
                                 data-widget-id="{{ $widget->layout_id }}">
                                <div class="widget-wrapper h-100">
                                    <div class="widget-header d-none">
                                        <div class="widget-title">{{ ucfirst(str_replace('widget_', '', str_replace('records.widget_', '', str_replace('players.widget_', '', $widget->widget_type)))) }}</div>
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
                                        @include('_widgets.' . str_replace('records.widget_', '', str_replace('players.widget_', '', $widget->widget_type)))
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Default widgets if no layout exists -->
                        <div class="grid-stack-item widget-container" 
                             gs-x="0" gs-y="0" gs-w="6" gs-h="8" 
                             data-widget-type="widget_notes">
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

                        <div class="grid-stack-item widget-container" 
                             gs-x="6" gs-y="0" gs-w="6" gs-h="8" 
                             data-widget-type="widget_trust_scores">
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

                        <div class="grid-stack-item widget-container" 
                             gs-x="0" gs-y="8" gs-w="12" gs-h="10" 
                             data-widget-type="widget_recent_activity">
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
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Dashboard Modal -->
    <div class="modal fade" id="createDashboardModal" tabindex="-1" aria-labelledby="createDashboardModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createDashboardModalLabel">
                        <i class="fas fa-plus me-2"></i>
                        Create New Dashboard
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newDashboardName" class="form-label">Dashboard Name</label>
                        <input type="text" class="form-control" id="newDashboardName" placeholder="Enter dashboard name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmCreateDashboard">
                        <i class="fas fa-plus me-2"></i>
                        Create Dashboard
                    </button>
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
                                    <div class="widget-option" data-widget-type="widget_notes" data-widget-category="general">
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
                                    <div class="widget-option" data-widget-type="widget_trust_scores" data-widget-category="general">
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
                                    <div class="widget-option" data-widget-type="widget_recent_activity" data-widget-category="general">
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
                                    <div class="widget-option" data-widget-type="widget_players" data-widget-category="players">
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
                                    <div class="widget-option" data-widget-type="widget_all_players" data-widget-category="players">
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
                                    <div class="widget-option" data-widget-type="records.widget_records" data-widget-category="records">
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
                                    <div class="widget-option" data-widget-type="records.widget_bans" data-widget-category="records">
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
                                    <div class="widget-option" data-widget-type="records.widget_warns" data-widget-category="records">
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
                                    <div class="widget-option" data-widget-type="records.widget_kicks" data-widget-category="records">
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
                                    <div class="widget-option" data-widget-type="records.widget_commends" data-widget-category="records">
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
                                    <div class="widget-option" data-widget-type="records.widget_trustscores" data-widget-category="records">
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

.dashboard-selector {
    margin-right: 20px;
}

.dashboard-selector .input-group {
    min-width: 200px;
    display: flex;
    align-items: stretch;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background-color: white;
}

.dashboard-selector .input-group .form-select {
    flex: 1;
    border-radius: 8px 0 0 8px;
    border: 2px solid #e3e6f0;
    border-right: 1px solid #e3e6f0;
    font-weight: 500;
    padding: 8px 12px;
}

.dashboard-selector .input-group .form-select:focus {
    border-color: #fd7e14;
    box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
    z-index: 2;
}

.dashboard-selector .input-group .btn {
    flex: 0 0 auto;
    border-radius: 0 8px 8px 0;
    border: 2px solid #e3e6f0;
    border-left: 1px solid #e3e6f0;
    padding: 8px 12px;
    background-color: transparent !important;
    background-image: none !important;
    color: #fd7e14 !important;
    transition: all 0.3s ease;
    min-width: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: none !important;
}

.dashboard-selector .input-group .btn:hover {
    border-color: #fd7e14 !important;
    background-color: #fd7e14 !important;
    background-image: none !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: none !important;
}

.dashboard-selector .input-group .btn:hover i.fas.fa-plus {
    color: white !important;
}

.dashboard-selector .input-group .btn i.fas.fa-plus {
    font-size: 14px;
    line-height: 1;
}

.dashboard-selector .input-group:focus-within .btn {
    border-color: #fd7e14;
    border-left-color: #fd7e14;
}

.dashboard-selector .input-group:focus-within .form-select {
    border-right-color: #fd7e14;
}

/* Style for the delete button */
.dashboard-selector #deleteDashboardBtn {
    margin-top: 8px;
    margin-left: 0;
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 6px;
}

/* Ensure proper input group spacing */
.dashboard-selector .input-group > * + * {
    margin-left: -1px;
}

/* Fix any Bootstrap input group issues */
.dashboard-selector .input-group .form-select:not(:first-child) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.dashboard-selector .input-group .btn:not(:last-child) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
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

.btn-outline-primary {
    border: 2px solid #fd7e14;
    color: #fd7e14;
    background: transparent;
}

.btn-outline-primary:hover {
    background: #fd7e14;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-danger {
    border: 2px solid #dc3545;
    color: #dc3545;
    background: transparent;
}

.btn-outline-danger:hover {
    background: #dc3545;
    color: white;
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
    
    .dashboard-selector .input-group {
        min-width: 100%;
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
// Base URL configuration for AJAX requests
const BASE_URL = '/web';

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
    const dashboardSelect = document.getElementById('dashboardSelect');
    const createDashboardBtn = document.getElementById('createDashboardBtn');
    const deleteDashboardBtn = document.getElementById('deleteDashboardBtn');

    let isCustomizeMode = false;
    let selectedWidgetType = null;
    let widgetCounter = 0;
    let currentDashboard = dashboardSelect.value;

    // Add Widget Modal Elements
    const addWidgetModal = new bootstrap.Modal(document.getElementById('addWidgetModal'));
    const widgetOptions = document.querySelectorAll('.widget-option');
    const confirmAddWidgetBtn = document.getElementById('confirmAddWidget');

    // Create Dashboard Modal Elements
    const createDashboardModal = new bootstrap.Modal(document.getElementById('createDashboardModal'));
    const newDashboardNameInput = document.getElementById('newDashboardName');
    const confirmCreateDashboardBtn = document.getElementById('confirmCreateDashboard');

    // Dashboard switching
    dashboardSelect.addEventListener('change', function() {
        const selectedDashboard = this.value;
        if (selectedDashboard !== currentDashboard) {
            switchDashboard(selectedDashboard);
        }
    });

    // Create dashboard
    createDashboardBtn.addEventListener('click', function() {
        newDashboardNameInput.value = '';
        createDashboardModal.show();
    });

    // Confirm create dashboard
    confirmCreateDashboardBtn.addEventListener('click', function() {
        const dashboardName = newDashboardNameInput.value.trim();
        if (dashboardName) {
            createDashboard(dashboardName);
        }
    });

    // Delete dashboard
    if (deleteDashboardBtn) {
        deleteDashboardBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this dashboard? This action cannot be undone.')) {
                deleteDashboard(currentDashboard);
            }
        });
    }

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

    function switchDashboard(dashboardName) {
        // Show loading indicator
        showNotification('Loading dashboard...', 'info', 0);
        
        // Load the new dashboard layout
        console.log('Switching to dashboard:', dashboardName);
        console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        fetch(`${BASE_URL}/verified/dashboard/layout?dashboard=${dashboardName}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'include'
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                if (response.status === 401 || response.status === 419) {
                    // Authentication error - redirect to login
                    console.log('Authentication error, redirecting to login');
                    window.location.href = '/login';
                    return;
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Response is not JSON:', contentType);
                // Try to get the text to see what we're actually getting
                return response.text().then(text => {
                    console.error('Response text:', text);
                    throw new Error('Response is not JSON');
                });
            }
            
            return response.json();
        })
        .then(data => {
            if (!data) return; // Handle redirect case
            
            // Clear current grid
            grid.removeAll();
            
            // Load new layout
            if (data.layout && data.layout.length > 0) {
                data.layout.forEach(widget => {
                    addWidgetToGrid(widget);
                });
            } else {
                // Create default layout for new dashboard
                createDefaultLayout();
            }
            
            currentDashboard = dashboardName;
            updateDashboardActions();
            showNotification(`${dashboardName.charAt(0).toUpperCase() + dashboardName.slice(1)} dashboard loaded!`, 'success');
        })
        .catch(error => {
            console.error('Error loading dashboard:', error);
            showNotification('Error loading dashboard. Please try again.', 'danger');
        });
    }

    function createDashboard(dashboardName) {
        fetch(`${BASE_URL}/verified/dashboard/create`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ dashboard_name: dashboardName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                showNotification(data.message, 'success');
                createDashboardModal.hide();
                
                // Add new dashboard to selector
                const option = document.createElement('option');
                option.value = dashboardName;
                option.textContent = dashboardName.charAt(0).toUpperCase() + dashboardName.slice(1) + ' Dashboard';
                dashboardSelect.appendChild(option);
                
                // Switch to new dashboard
                dashboardSelect.value = dashboardName;
                switchDashboard(dashboardName);
            } else {
                showNotification(data.error || 'Error creating dashboard', 'danger');
            }
        })
        .catch(error => {
            console.error('Error creating dashboard:', error);
            showNotification('Error creating dashboard. Please try again.', 'danger');
        });
    }

    function deleteDashboard(dashboardName) {
        fetch(`${BASE_URL}/verified/dashboard/delete`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ dashboard_name: dashboardName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                showNotification(data.message, 'success');
                
                // Remove dashboard from selector
                const option = dashboardSelect.querySelector(`option[value="${dashboardName}"]`);
                if (option) {
                    option.remove();
                }
                
                // Switch to main dashboard
                dashboardSelect.value = 'main';
                switchDashboard('main');
            } else {
                showNotification(data.error || 'Error deleting dashboard', 'danger');
            }
        })
        .catch(error => {
            console.error('Error deleting dashboard:', error);
            showNotification('Error deleting dashboard. Please try again.', 'danger');
        });
    }

    function updateDashboardActions() {
        // Update delete button visibility
        if (deleteDashboardBtn) {
            if (currentDashboard === 'main') {
                deleteDashboardBtn.style.display = 'none';
            } else {
                deleteDashboardBtn.style.display = 'inline-block';
            }
        }
    }

    function addWidgetToGrid(widget) {
        const widgetHTML = `
            <div class="grid-stack-item widget-container" 
                 gs-x="${widget.col}" gs-y="${widget.row}" gs-w="${widget.size_x}" gs-h="${widget.size_y}" 
                 data-widget-type="${widget.widget_type}"
                 data-widget-id="${widget.layout_id}">
                <div class="widget-wrapper h-100">
                    <div class="widget-header ${isCustomizeMode ? '' : 'd-none'}">
                        <div class="widget-title">${getWidgetTitle(widget.widget_type)}</div>
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
                        ${getWidgetContent(widget.widget_type)}
                    </div>
                </div>
            </div>`;
        
        grid.addWidget(widgetHTML);
    }

    function createDefaultLayout() {
        const defaultWidgets = [
            { widget_type: 'widget_notes', col: 0, row: 0, size_x: 6, size_y: 8 },
            { widget_type: 'widget_trust_scores', col: 6, row: 0, size_x: 6, size_y: 8 },
            { widget_type: 'widget_recent_activity', col: 0, row: 8, size_x: 12, size_y: 10 }
        ];

        defaultWidgets.forEach(widget => {
            addWidgetToGrid(widget);
        });
    }

    function getWidgetTitle(widgetType) {
        const titleMap = {
            'widget_notes': 'Notes',
            'widget_trust_scores': 'Trust Scores',
            'widget_recent_activity': 'Recent Activity',
            'widget_players': 'Players',
            'widget_all_players': 'All Players',
            'records.widget_records': 'All Records',
            'records.widget_bans': 'Bans',
            'records.widget_warns': 'Warnings',
            'records.widget_kicks': 'Kicks',
            'records.widget_commends': 'Commends',
            'records.widget_trustscores': 'Trust Score Records'
        };
        
        return titleMap[widgetType] || widgetType.replace('widget_', '').replace('records.widget_', '').replace('players.widget_', '');
    }

    function getWidgetContent(widgetType) {
        // For now, return placeholder content
        // In a real implementation, you'd render the actual widget content
        return `<div class="blank-widget">
            <div class="blank-widget-content">
                <div class="blank-widget-icon">
                    <i class="fas fa-puzzle-piece"></i>
                </div>
                <h5>${getWidgetTitle(widgetType)}</h5>
                <p>Widget content will be loaded here</p>
            </div>
        </div>`;
    }

    function enableCustomizeMode() {
        isCustomizeMode = true;
        
        // Toggle UI
        normalModeButtons.classList.add('d-none');
        customizeModeButtons.classList.remove('d-none');
        
        // Enable grid interactions
        grid.enableMove(true);
        grid.enableResize(true);
        
        // Add customize styling to all widgets
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
        
        // Remove customize styling from all widgets
        document.querySelectorAll('.widget-container').forEach(widget => {
            widget.classList.remove('customize-mode');
        });
        
        // Hide widget headers
        document.querySelectorAll('.widget-header').forEach(header => {
            header.classList.add('d-none');
        });
        
        // Save layout
        saveLayout();
    }

    function resetLayout() {
        // Reset to default positions
        const defaultLayout = [
            { x: 0, y: 0, w: 6, h: 8, id: 'widget_notes' },
            { x: 6, y: 0, w: 6, h: 8, id: 'widget_trust_scores' },
            { x: 0, y: 8, w: 12, h: 10, id: 'widget_recent_activity' }
        ];
        
        // Clear current grid
        grid.removeAll();
        
        // Apply default layout
        defaultLayout.forEach(item => {
            const widget = {
                widget_type: item.id,
                col: item.x,
                row: item.y,
                size_x: item.w,
                size_y: item.h
            };
            addWidgetToGrid(widget);
        });
    }

    function saveLayout() {
        const widgetDataList = [];
        
        grid.getGridItems().forEach(item => {
            const node = item.gridstackNode;
            const widgetType = item.getAttribute('data-widget-type');
            const widgetId = item.getAttribute('data-widget-id');
            
            widgetDataList.push({
                widgetType: widgetType,
                widgetId: widgetId,
                x: node.x,
                y: node.y,
                w: node.w,
                h: node.h
            });
        });

        // Show saving indicator
        const savingToast = showNotification('Saving dashboard layout...', 'info', 0);

        // Send layout to backend
        fetch(`${BASE_URL}/verified/dashboard/save`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                dashboard: currentDashboard,
                widgets: widgetDataList
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message && data.message.includes('successfully')) {
                showNotification('Dashboard saved successfully!', 'success');
            } else {
                showNotification('Error saving dashboard: ' + (data.message || 'Unknown error'), 'danger');
            }
        })
        .catch(error => {
            console.error('Error saving layout:', error);
            showNotification('Error saving dashboard layout. Please try again.', 'danger');
        });
    }

    // Add blank widget to grid function
    function addBlankWidget(widgetType) {
        widgetCounter++;
        const widgetId = `new_${widgetCounter}`;
        
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
            'widget_notes': { title: 'Notes', icon: 'fas fa-sticky-note' },
            'widget_trust_scores': { title: 'Trust Scores', icon: 'fas fa-shield-alt' },
            'widget_recent_activity': { title: 'Recent Activity', icon: 'fas fa-clock' },
            'widget_players': { title: 'Players', icon: 'fas fa-user' },
            'widget_all_players': { title: 'All Players', icon: 'fas fa-users' },
            'records.widget_records': { title: 'All Records', icon: 'fas fa-list' },
            'records.widget_bans': { title: 'Bans', icon: 'fas fa-ban' },
            'records.widget_warns': { title: 'Warnings', icon: 'fas fa-exclamation-triangle' },
            'records.widget_kicks': { title: 'Kicks', icon: 'fas fa-sign-out-alt' },
            'records.widget_commends': { title: 'Commends', icon: 'fas fa-thumbs-up' },
            'records.widget_trustscores': { title: 'Trust Score Records', icon: 'fas fa-star' }
        };
        
        return widgetInfoMap[widgetType] || { 
            title: widgetType.charAt(0).toUpperCase() + widgetType.slice(1), 
            icon: 'fas fa-puzzle-piece' 
        };
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

    // Initialize dashboard actions
    updateDashboardActions();
});
</script>
