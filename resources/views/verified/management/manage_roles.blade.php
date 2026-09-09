<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('_partials._html_header')
    <body class="background-sizing gta-bg1">
        <div class="container-fluid master-contain">
            @include('_partials._toast')
            @include('_partials._sidebar')
            <div class="content-wrapper">
                <!-- Header Section -->
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-user-shield me-2"></i>
                            Manage Roles
                        </h1>
                        <p class="page-description">Configure roles and their permissions</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                        <i class="fas fa-plus me-2"></i>
                        Add Role
                    </button>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-purple">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">3</h3>
                                <p class="stat-label">Total Roles</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-green">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">3</h3>
                                <p class="stat-label">Active Roles</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-blue">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">12</h3>
                                <p class="stat-label">Permissions</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon stat-icon-orange">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-content">
                                <h3 class="stat-number">4</h3>
                                <p class="stat-label">Staff Assigned</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" placeholder="Search roles..." id="roleSearch">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Roles Section -->
                <div class="section-header">
                    <h2>Roles (3)</h2>
                </div>

                <!-- Roles Table -->
                <div class="table-responsive">
                    <table class="table table-hover roles-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Level</th>
                                <th>Permissions</th>
                                <th>Staff Count</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-role-id="1">
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <i class="fas fa-crown"></i>
                                        </div>
                                        <span>Admin</span>
                                    </div>
                                </td>
                                <td>Full system access and management</td>
                                <td>
                                    <span class="role-badge role-admin">
                                        <i class="fas fa-star me-1"></i>
                                        Level 100
                                    </span>
                                </td>
                                <td>All (12) 
                                    <button class="btn btn-sm btn-outline-info ms-2 p-1" onclick="viewPermissions(this)" title="View permissions">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                                <td>
                                    <span class="actions-count">1</span>
                                </td>
                                <td>
                                    <span class="status-badge status-active">Active</span>
                                </td>
                                <td>2023-01-01</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="openEditRoleModal(this)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="openDeleteRoleModal(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-role-id="2">
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <span>Moderator</span>
                                    </div>
                                </td>
                                <td>Player moderation and server management</td>
                                <td>
                                    <span class="role-badge role-moderator">
                                        <i class="fas fa-star me-1"></i>
                                        Level 50
                                    </span>
                                </td>
                                <td>8 of 12
                                    <button class="btn btn-sm btn-outline-info ms-2 p-1" onclick="viewPermissions(this)" title="View permissions">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                                <td>
                                    <span class="actions-count">2</span>
                                </td>
                                <td>
                                    <span class="status-badge status-active">Active</span>
                                </td>
                                <td>2023-01-01</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="openEditRoleModal(this)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="openDeleteRoleModal(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-role-id="3">
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <i class="fas fa-hands-helping"></i>
                                        </div>
                                        <span>Helper</span>
                                    </div>
                                </td>
                                <td>Basic player assistance and support</td>
                                <td>
                                    <span class="role-badge role-helper">
                                        <i class="fas fa-star me-1"></i>
                                        Level 25
                                    </span>
                                </td>
                                <td>4 of 12
                                    <button class="btn btn-sm btn-outline-info ms-2 p-1" onclick="viewPermissions(this)" title="View permissions">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                                <td>
                                    <span class="actions-count">1</span>
                                </td>
                                <td>
                                    <span class="status-badge status-active">Active</span>
                                </td>
                                <td>2023-01-01</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="openEditRoleModal(this)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="openDeleteRoleModal(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if(false) {{-- Change to true when no roles exist --}}
                    <div class="no-data-message">
                        <div class="text-center py-5">
                            <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No roles found</h4>
                            <p class="text-muted">Create your first role to get started.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Add Role Modal -->
        <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRoleModalLabel">
                            <i class="fas fa-plus me-2"></i>
                            Add New Role
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addRoleForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="roleName" class="form-label">Role Name</label>
                                    <input type="text" class="form-control" id="roleName" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="roleLevel" class="form-label">Role Level</label>
                                    <input type="number" class="form-control" id="roleLevel" min="1" max="100" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="roleIcon" class="form-label">Role Icon</label>
                                    <select class="form-select" id="roleIcon">
                                        <option value="fas fa-crown">👑 Crown</option>
                                        <option value="fas fa-shield-alt">🛡️ Shield</option>
                                        <option value="fas fa-hands-helping">🤝 Helping Hands</option>
                                        <option value="fas fa-star">⭐ Star</option>
                                        <option value="fas fa-medal">🏅 Medal</option>
                                        <option value="fas fa-user-tie">👔 User Tie</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="roleColor" class="form-label">Role Color</label>
                                    <select class="form-select" id="roleColor">
                                        <option value="text-danger">Red</option>
                                        <option value="text-primary">Blue</option>
                                        <option value="text-success">Green</option>
                                        <option value="text-warning">Yellow</option>
                                        <option value="text-info">Cyan</option>
                                        <option value="text-purple">Purple</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="roleDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="roleDescription" rows="3" placeholder="Describe this role's purpose and responsibilities..."></textarea>
                            </div>
                            
                            <!-- Permissions Section -->
                            <div class="permissions-section">
                                <h6 class="mb-3">
                                    <i class="fas fa-key me-2"></i>
                                    Permissions
                                </h6>
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <strong>Security Note:</strong> You can only assign permissions that you currently have. Roles cannot be granted higher privileges than your own role level.
                                </div>
                                <div class="permissions-grid">
                                    <div class="permission-category">
                                        <h6 class="permission-category-title">Player Management</h6>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_ban_players" class="form-check-input">
                                            <label for="perm_ban_players" class="form-check-label">Ban Players</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_kick_players" class="form-check-input">
                                            <label for="perm_kick_players" class="form-check-label">Kick Players</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_warn_players" class="form-check-input">
                                            <label for="perm_warn_players" class="form-check-label">Warn Players</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_commend_players" class="form-check-input">
                                            <label for="perm_commend_players" class="form-check-label">Commend Players</label>
                                        </div>
                                    </div>
                                    
                                    <div class="permission-category">
                                        <h6 class="permission-category-title">System Access</h6>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_view_dashboard" class="form-check-input">
                                            <label for="perm_view_dashboard" class="form-check-label">View Dashboard</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_view_records" class="form-check-input">
                                            <label for="perm_view_records" class="form-check-label">View Records</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_view_players" class="form-check-input">
                                            <label for="perm_view_players" class="form-check-label">View Players</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_export_data" class="form-check-input">
                                            <label for="perm_export_data" class="form-check-label">Export Data</label>
                                        </div>
                                    </div>
                                    
                                    <div class="permission-category">
                                        <h6 class="permission-category-title">Administration</h6>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_manage_staff" class="form-check-input">
                                            <label for="perm_manage_staff" class="form-check-label">Manage Staff</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_edit_staff" class="form-check-input">
                                            <label for="perm_edit_staff" class="form-check-label">Edit Staff</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_manage_roles" class="form-check-input">
                                            <label for="perm_manage_roles" class="form-check-label">Manage Roles</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_edit_roles" class="form-check-input">
                                            <label for="perm_edit_roles" class="form-check-label">Edit Roles</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_manage_tokens" class="form-check-input">
                                            <label for="perm_manage_tokens" class="form-check-label">Manage Tokens</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_system_settings" class="form-check-input">
                                            <label for="perm_system_settings" class="form-check-label">System Settings</label>
                                        </div>
                                    </div>
                                    
                                    <div class="permission-category">
                                        <h6 class="permission-category-title">Trust Scores</h6>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_trustscore_update" class="form-check-input">
                                            <label for="perm_trustscore_update" class="form-check-label">Update Trust Scores</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="perm_trustscore_reset" class="form-check-input">
                                            <label for="perm_trustscore_reset" class="form-check-label">Reset Trust Scores</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="addRoleForm">
                            <i class="fas fa-plus me-2"></i>
                            Create Role
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Role Modal -->
        <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoleModalLabel">
                            <i class="fas fa-edit me-2"></i>
                            Edit Role
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editRoleForm">
                            <input type="hidden" id="editRoleId">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editRoleName" class="form-label">Role Name</label>
                                    <input type="text" class="form-control" id="editRoleName" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editRoleLevel" class="form-label">Role Level</label>
                                    <input type="number" class="form-control" id="editRoleLevel" min="1" max="100" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editRoleIcon" class="form-label">Role Icon</label>
                                    <select class="form-select" id="editRoleIcon">
                                        <option value="fas fa-crown">👑 Crown</option>
                                        <option value="fas fa-shield-alt">🛡️ Shield</option>
                                        <option value="fas fa-hands-helping">🤝 Helping Hands</option>
                                        <option value="fas fa-star">⭐ Star</option>
                                        <option value="fas fa-medal">🏅 Medal</option>
                                        <option value="fas fa-user-tie">👔 User Tie</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editRoleColor" class="form-label">Role Color</label>
                                    <select class="form-select" id="editRoleColor">
                                        <option value="text-danger">Red</option>
                                        <option value="text-primary">Blue</option>
                                        <option value="text-success">Green</option>
                                        <option value="text-warning">Yellow</option>
                                        <option value="text-info">Cyan</option>
                                        <option value="text-purple">Purple</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editRoleStatus" class="form-label">Status</label>
                                    <select class="form-select" id="editRoleStatus">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editStaffCount" class="form-label">Staff Assigned</label>
                                    <input type="number" class="form-control" id="editStaffCount" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="editRoleDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editRoleDescription" rows="3"></textarea>
                            </div>
                            
                            <!-- Permissions Section -->
                            <div class="permissions-section">
                                <h6 class="mb-3">
                                    <i class="fas fa-key me-2"></i>
                                    Permissions
                                </h6>
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    <strong>Security Note:</strong> You can only assign permissions that you currently have. Roles cannot be granted higher privileges than your own role level.
                                </div>
                                <div class="permissions-grid">
                                    <div class="permission-category">
                                        <h6 class="permission-category-title">Player Management</h6>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_ban_players" class="form-check-input">
                                            <label for="edit_perm_ban_players" class="form-check-label">Ban Players</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_kick_players" class="form-check-input">
                                            <label for="edit_perm_kick_players" class="form-check-label">Kick Players</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_warn_players" class="form-check-input">
                                            <label for="edit_perm_warn_players" class="form-check-label">Warn Players</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_commend_players" class="form-check-input">
                                            <label for="edit_perm_commend_players" class="form-check-label">Commend Players</label>
                                        </div>
                                    </div>
                                    
                                    <div class="permission-category">
                                        <h6 class="permission-category-title">System Access</h6>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_view_dashboard" class="form-check-input">
                                            <label for="edit_perm_view_dashboard" class="form-check-label">View Dashboard</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_view_records" class="form-check-input">
                                            <label for="edit_perm_view_records" class="form-check-label">View Records</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_view_players" class="form-check-input">
                                            <label for="edit_perm_view_players" class="form-check-label">View Players</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_export_data" class="form-check-input">
                                            <label for="edit_perm_export_data" class="form-check-label">Export Data</label>
                                        </div>
                                    </div>
                                    
                                    <div class="permission-category">
                                        <h6 class="permission-category-title">Administration</h6>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_manage_staff" class="form-check-input">
                                            <label for="edit_perm_manage_staff" class="form-check-label">Manage Staff</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_edit_staff" class="form-check-input">
                                            <label for="edit_perm_edit_staff" class="form-check-label">Edit Staff</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_manage_roles" class="form-check-input">
                                            <label for="edit_perm_manage_roles" class="form-check-label">Manage Roles</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_edit_roles" class="form-check-input">
                                            <label for="edit_perm_edit_roles" class="form-check-label">Edit Roles</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_manage_tokens" class="form-check-input">
                                            <label for="edit_perm_manage_tokens" class="form-check-label">Manage Tokens</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_system_settings" class="form-check-input">
                                            <label for="edit_perm_system_settings" class="form-check-label">System Settings</label>
                                        </div>
                                    </div>
                                    
                                    <div class="permission-category">
                                        <h6 class="permission-category-title">Trust Scores</h6>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_trustscore_update" class="form-check-input">
                                            <label for="edit_perm_trustscore_update" class="form-check-label">Update Trust Scores</label>
                                        </div>
                                        <div class="permission-item">
                                            <input type="checkbox" id="edit_perm_trustscore_reset" class="form-check-input">
                                            <label for="edit_perm_trustscore_reset" class="form-check-label">Reset Trust Scores</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="editRoleForm">
                            <i class="fas fa-save me-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Role Modal -->
        <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteRoleModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Confirm Role Deletion
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-user-shield text-danger" style="font-size: 48px;"></i>
                        </div>
                        <h6 class="text-center mb-3">Are you sure you want to delete this role?</h6>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>This action cannot be undone.</strong> All staff members with this role will lose their permissions.
                        </div>
                        <div class="role-details mt-3">
                            <div class="row">
                                <div class="col-6"><strong>Role Name:</strong></div>
                                <div class="col-6" id="deleteRoleName"></div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Level:</strong></div>
                                <div class="col-6" id="deleteRoleLevel"></div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Staff Count:</strong></div>
                                <div class="col-6" id="deleteRoleStaffCount"></div>
                            </div>
                        </div>
                        <input type="hidden" id="deleteRoleId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteRoleBtn">
                            <i class="fas fa-trash me-2"></i>
                            Delete Role
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Permissions Modal -->
        <div class="modal fade" id="viewPermissionsModal" tabindex="-1" aria-labelledby="viewPermissionsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewPermissionsModalLabel">
                            <i class="fas fa-key me-2"></i>
                            Role Permissions
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="role-summary mb-3">
                            <h6 id="viewRoleName"></h6>
                            <p class="text-muted" id="viewRoleDescription"></p>
                        </div>
                        <div class="permissions-display" id="permissionsDisplay">
                            <!-- Permissions will be populated by JavaScript -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        @include('_partials._html_footer')

        <style>
        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #fd7e14 0%, #ff9f40 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 12px;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-info span {
            font-weight: 600;
            color: #2c3e50;
        }

        .role-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        .role-badge .fas {
            font-size: 0.8rem;
            margin-right: 4px;
        }

        .role-admin {
            background: #dc3545;
            color: white;
        }

        .role-moderator {
            background: #ffc107;
            color: #000;
        }

        .role-helper {
            background: #28a745;
            color: white;
        }

        .actions-count {
            font-weight: 600;
            color: #fd7e14;
            font-size: 1.1rem;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .action-buttons .btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-1px);
        }

        .no-data-message {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        /* Table styling to match existing */
        .roles-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .roles-table thead th {
            background: #fd7e14;
            border-bottom: 2px solid #fd7e14;
            font-weight: 600;
            color: white;
            padding: 12px 15px;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .roles-table tbody tr {
            border-bottom: 1px solid #f1f3f4;
            transition: all 0.2s ease;
        }

        .roles-table tbody tr:hover {
            background: #f8f9fa;
        }

        .roles-table tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        .roles-table tbody tr:last-child {
            border-bottom: none;
        }

        /* Modal and permissions styling */
        .permissions-section {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            background: #f8f9fa;
        }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .permission-category {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .permission-category-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 5px;
        }

        .permission-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .permission-item .form-check-input {
            margin-right: 8px;
        }

        .permission-item .form-check-label {
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .permissions-display {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .permission-group {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .permission-group h6 {
            color: #495057;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .permission-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .permission-list li {
            padding: 2px 0;
            font-size: 0.85rem;
        }

        .permission-list .granted {
            color: #28a745;
        }

        .permission-list .denied {
            color: #dc3545;
        }

        .stat-icon-purple {
            background: linear-gradient(135deg, #6f42c1 0%, #8b5cf6 100%);
        }

        .text-purple {
            color: #6f42c1 !important;
        }
        </style>

        <script>
            // Search functionality
            document.getElementById('roleSearch').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.roles-table tbody tr');
                
                rows.forEach(row => {
                    const roleName = row.cells[0].querySelector('span').textContent.toLowerCase();
                    const description = row.cells[1].textContent.toLowerCase();
                    
                    if (roleName.includes(searchTerm) || description.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Status filter functionality
            document.getElementById('statusFilter').addEventListener('change', function(e) {
                const selectedStatus = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.roles-table tbody tr');
                
                rows.forEach(row => {
                    const statusCell = row.cells[5].textContent.toLowerCase();
                    
                    if (selectedStatus === '' || statusCell.includes(selectedStatus)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Add role form submission
            document.getElementById('addRoleForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    name: document.getElementById('roleName').value,
                    level: document.getElementById('roleLevel').value,
                    icon: document.getElementById('roleIcon').value,
                    color: document.getElementById('roleColor').value,
                    description: document.getElementById('roleDescription').value,
                    permissions: getSelectedPermissions('')
                };
                
                console.log('Adding role:', formData);
                alert('Role created successfully!');
                bootstrap.Modal.getInstance(document.getElementById('addRoleModal')).hide();
                this.reset();
            });

            // Edit role form submission
            document.getElementById('editRoleForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const roleId = document.getElementById('editRoleId').value;
                const formData = {
                    id: roleId,
                    name: document.getElementById('editRoleName').value,
                    level: document.getElementById('editRoleLevel').value,
                    icon: document.getElementById('editRoleIcon').value,
                    color: document.getElementById('editRoleColor').value,
                    status: document.getElementById('editRoleStatus').value,
                    description: document.getElementById('editRoleDescription').value,
                    permissions: getSelectedPermissions('edit_')
                };
                
                console.log('Updating role:', formData);
                alert('Role updated successfully!');
                bootstrap.Modal.getInstance(document.getElementById('editRoleModal')).hide();
            });

            // Open edit role modal
            function openEditRoleModal(button) {
                const row = button.closest('tr');
                const cells = row.cells;
                
                const roleId = row.dataset.roleId;
                const roleName = cells[0].querySelector('span').textContent;
                const description = cells[1].textContent;
                const level = cells[2].querySelector('.role-badge').textContent.match(/\d+/)[0];
                const staffCount = cells[4].textContent;
                const status = cells[5].textContent.toLowerCase().includes('active') ? 'active' : 'inactive';
                
                document.getElementById('editRoleId').value = roleId;
                document.getElementById('editRoleName').value = roleName;
                document.getElementById('editRoleLevel').value = level;
                document.getElementById('editRoleDescription').value = description;
                document.getElementById('editStaffCount').value = staffCount;
                document.getElementById('editRoleStatus').value = status;
                
                // Set mock permissions based on role
                setMockPermissions('edit_', roleName.toLowerCase());
                
                new bootstrap.Modal(document.getElementById('editRoleModal')).show();
            }

            // Open delete role modal
            function openDeleteRoleModal(button) {
                const row = button.closest('tr');
                const cells = row.cells;
                
                const roleId = row.dataset.roleId;
                const roleName = cells[0].querySelector('span').textContent;
                const level = cells[2].querySelector('.role-badge').textContent;
                const staffCount = cells[4].textContent;
                
                document.getElementById('deleteRoleId').value = roleId;
                document.getElementById('deleteRoleName').textContent = roleName;
                document.getElementById('deleteRoleLevel').textContent = level;
                document.getElementById('deleteRoleStaffCount').textContent = staffCount;
                
                new bootstrap.Modal(document.getElementById('deleteRoleModal')).show();
            }

            // View permissions
            function viewPermissions(button) {
                const row = button.closest('tr');
                const cells = row.cells;
                
                const roleName = cells[0].querySelector('span').textContent;
                const description = cells[1].textContent;
                
                document.getElementById('viewRoleName').textContent = roleName;
                document.getElementById('viewRoleDescription').textContent = description;
                
                // Generate permissions display
                const permissionsDisplay = document.getElementById('permissionsDisplay');
                permissionsDisplay.innerHTML = generatePermissionsDisplay(roleName.toLowerCase());
                
                new bootstrap.Modal(document.getElementById('viewPermissionsModal')).show();
            }

            // Confirm delete role
            document.getElementById('confirmDeleteRoleBtn').addEventListener('click', function() {
                const roleId = document.getElementById('deleteRoleId').value;
                const roleName = document.getElementById('deleteRoleName').textContent;
                
                console.log('Deleting role:', { roleId, roleName });
                
                // Remove the row
                const rows = document.querySelectorAll('.roles-table tbody tr');
                rows.forEach(row => {
                    if (row.dataset.roleId === roleId) {
                        row.style.animation = 'fadeOut 0.3s ease-out';
                        setTimeout(() => {
                            row.remove();
                        }, 300);
                    }
                });
                
                bootstrap.Modal.getInstance(document.getElementById('deleteRoleModal')).hide();
                alert('Role deleted successfully!');
            });

            // Helper functions
            function getSelectedPermissions(prefix) {
                const permissions = [];
                const checkboxes = document.querySelectorAll(`input[id^="${prefix}perm_"]:checked`);
                checkboxes.forEach(cb => {
                    permissions.push(cb.id.replace(prefix, ''));
                });
                return permissions;
            }

            function setMockPermissions(prefix, roleName) {
                // Clear all checkboxes first
                document.querySelectorAll(`input[id^="${prefix}perm_"]`).forEach(cb => cb.checked = false);
                
                // Set permissions based on role
                const permissions = {
                    admin: ['perm_ban_players', 'perm_kick_players', 'perm_warn_players', 'perm_commend_players', 'perm_view_dashboard', 'perm_view_records', 'perm_view_players', 'perm_export_data', 'perm_manage_staff', 'perm_edit_staff', 'perm_manage_roles', 'perm_edit_roles', 'perm_manage_tokens', 'perm_system_settings', 'perm_trustscore_update', 'perm_trustscore_reset'],
                    moderator: ['perm_ban_players', 'perm_kick_players', 'perm_warn_players', 'perm_commend_players', 'perm_view_dashboard', 'perm_view_records', 'perm_view_players', 'perm_export_data', 'perm_edit_staff', 'perm_trustscore_update', 'perm_trustscore_reset'],
                    helper: ['perm_warn_players', 'perm_commend_players', 'perm_view_dashboard', 'perm_view_records']
                };
                
                if (permissions[roleName]) {
                    permissions[roleName].forEach(perm => {
                        const checkbox = document.getElementById(prefix + perm);
                        if (checkbox) checkbox.checked = true;
                    });
                }
            }

            function generatePermissionsDisplay(roleName) {
                const permissions = {
                    admin: {
                        'Player Management': ['Ban Players', 'Kick Players', 'Warn Players', 'Commend Players'],
                        'System Access': ['View Dashboard', 'View Records', 'View Players', 'Export Data'],
                        'Administration': ['Manage Staff', 'Edit Staff', 'Manage Roles', 'Edit Roles', 'Manage Tokens', 'System Settings'],
                        'Trust Scores': ['Update Trust Scores', 'Reset Trust Scores']
                    },
                    moderator: {
                        'Player Management': ['Ban Players', 'Kick Players', 'Warn Players', 'Commend Players'],
                        'System Access': ['View Dashboard', 'View Records', 'View Players', 'Export Data'],
                        'Administration': ['Edit Staff'],
                        'Trust Scores': ['Update Trust Scores', 'Reset Trust Scores']
                    },
                    helper: {
                        'Player Management': ['Warn Players', 'Commend Players'],
                        'System Access': ['View Dashboard', 'View Records'],
                        'Administration': [],
                        'Trust Scores': []
                    }
                };

                const rolePerms = permissions[roleName] || {};
                let html = '';

                Object.keys(rolePerms).forEach(category => {
                    html += `<div class="permission-group">
                        <h6>${category}</h6>
                        <ul class="permission-list">`;
                    
                    if (rolePerms[category].length === 0) {
                        html += '<li class="denied">No permissions</li>';
                    } else {
                        rolePerms[category].forEach(perm => {
                            html += `<li class="granted">✓ ${perm}</li>`;
                        });
                    }
                    
                    html += '</ul></div>';
                });

                return html;
            }
        </script>
    </body>
</html> 