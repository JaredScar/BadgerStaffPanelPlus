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
                            <i class="fas fa-users me-2"></i>
                            Manage Staff
                        </h1>
                        <p class="page-description">Manage staff members and their permissions</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                        <i class="fas fa-plus me-2"></i>
                        Add Staff Member
                    </button>
                </div>

                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon stat-icon-red">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">1</h3>
                                        <p class="stat-label">Admins</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon stat-icon-blue">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">2</h3>
                                        <p class="stat-label">Moderators</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon stat-icon-green">
                                        <i class="fas fa-hands-helping"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">1</h3>
                                        <p class="stat-label">Helpers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon stat-icon-orange">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">3</h3>
                                        <p class="stat-label">Active Staff</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Search and Filter Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" class="form-control" placeholder="Search staff..." id="staffSearch">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" id="roleFilter">
                                    <option value="">All Roles</option>
                                    <option value="admin">Admin</option>
                                    <option value="moderator">Moderator</option>
                                    <option value="helper">Helper</option>
                                </select>
                            </div>
                        </div>

                        <!-- Staff Members Section -->
                        <div class="section-header">
                            <h2>Staff Members (4)</h2>
                        </div>

                        <!-- Staff Table -->
                        <div class="table-responsive">
                            <table class="table table-hover staff-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Discord</th>
                                        <th>Role</th>
                                        <th>Join Date</th>
                                        <th>Last Active</th>
                                        <th>Actions Count</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-staff-id="1">
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span>AdminUser</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="discord-tag">AdminUser#0001</span>
                                        </td>
                                        <td>
                                            <span class="role-badge role-admin">
                                                <i class="fas fa-crown me-1"></i>
                                                Admin
                                            </span>
                                        </td>
                                        <td>2023-06-01</td>
                                        <td>
                                            <span class="status-online">Currently online</span>
                                        </td>
                                        <td>
                                            <span class="actions-count">245</span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-active">Active</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="openEditModal(this)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="openDeleteModal(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr data-staff-id="2">
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span>ModUser</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="discord-tag">ModUser#0002</span>
                                        </td>
                                        <td>
                                            <span class="role-badge role-moderator">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Moderator
                                            </span>
                                        </td>
                                        <td>2023-08-15</td>
                                        <td>2 hours ago</td>
                                        <td>
                                            <span class="actions-count">156</span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-active">Active</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="openEditModal(this)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="openDeleteModal(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr data-staff-id="3">
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span>HelperUser</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="discord-tag">HelperUser#0003</span>
                                        </td>
                                        <td>
                                            <span class="role-badge role-helper">
                                                <i class="fas fa-hands-helping me-1"></i>
                                                Helper
                                            </span>
                                        </td>
                                        <td>2023-11-20</td>
                                        <td>1 day ago</td>
                                        <td>
                                            <span class="actions-count">89</span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-active">Active</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="openEditModal(this)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="openDeleteModal(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr data-staff-id="4">
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <span>FormerMod</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="discord-tag">FormerMod#0004</span>
                                        </td>
                                        <td>
                                            <span class="role-badge role-moderator">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Moderator
                                            </span>
                                        </td>
                                        <td>2023-05-10</td>
                                        <td>2 weeks ago</td>
                                        <td>
                                            <span class="actions-count">78</span>
                                        </td>
                                        <td>
                                            <span class="status-badge status-inactive">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-outline-primary" title="Edit" onclick="openEditModal(this)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="openDeleteModal(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Staff Modal -->
        <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStaffModalLabel">
                            <i class="fas fa-user-plus me-2"></i>
                            Add Staff Member
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addStaffForm">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="staffName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="staffName" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="discordTag" class="form-label">Discord Tag</label>
                                    <input type="text" class="form-control" id="discordTag" placeholder="Username#0000" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="staffRole" class="form-label">Role</label>
                                    <select class="form-select" id="staffRole" required>
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="moderator">Moderator</option>
                                        <option value="helper">Helper</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="joinDate" class="form-label">Join Date</label>
                                    <input type="date" class="form-control" id="joinDate" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="staffNotes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="staffNotes" rows="3" placeholder="Additional notes about this staff member..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="addStaffForm">
                            <i class="fas fa-plus me-2"></i>
                            Add Staff Member
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Staff Modal -->
        <div class="modal fade" id="editStaffModal" tabindex="-1" aria-labelledby="editStaffModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStaffModalLabel">
                            <i class="fas fa-user-edit me-2"></i>
                            Edit Staff Member
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editStaffForm">
                            <input type="hidden" id="editStaffId">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editStaffName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="editStaffName" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editDiscordTag" class="form-label">Discord Tag</label>
                                    <input type="text" class="form-control" id="editDiscordTag" placeholder="Username#0000" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editStaffRole" class="form-label">Role</label>
                                    <select class="form-select" id="editStaffRole" required>
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="moderator">Moderator</option>
                                        <option value="helper">Helper</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editJoinDate" class="form-label">Join Date</label>
                                    <input type="date" class="form-control" id="editJoinDate" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editStaffStatus" class="form-label">Status</label>
                                    <select class="form-select" id="editStaffStatus" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editActionsCount" class="form-label">Actions Count</label>
                                    <input type="number" class="form-control" id="editActionsCount" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="editStaffNotes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="editStaffNotes" rows="3" placeholder="Additional notes about this staff member..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" form="editStaffForm">
                            <i class="fas fa-save me-2"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteStaffModal" tabindex="-1" aria-labelledby="deleteStaffModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteStaffModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Confirm Deletion
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-user-times text-danger" style="font-size: 48px;"></i>
                        </div>
                        <h6 class="text-center mb-3">Are you sure you want to delete this staff member?</h6>
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>This action cannot be undone.</strong> All associated data and permissions will be permanently removed.
                        </div>
                        <div class="staff-details mt-3">
                            <div class="row">
                                <div class="col-6"><strong>Name:</strong></div>
                                <div class="col-6" id="deleteStaffName"></div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Discord:</strong></div>
                                <div class="col-6" id="deleteStaffDiscord"></div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Role:</strong></div>
                                <div class="col-6" id="deleteStaffRole"></div>
                            </div>
                            <div class="row">
                                <div class="col-6"><strong>Actions:</strong></div>
                                <div class="col-6" id="deleteStaffActions"></div>
                            </div>
                        </div>
                        <input type="hidden" id="deleteStaffId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                            <i class="fas fa-trash me-2"></i>
                            Delete Staff Member
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('_partials._html_footer')

        <script>
            // Search functionality
            document.getElementById('staffSearch').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.staff-table tbody tr');
                
                rows.forEach(row => {
                    const name = row.cells[0].textContent.toLowerCase();
                    const discord = row.cells[1].textContent.toLowerCase();
                    const role = row.cells[2].textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || discord.includes(searchTerm) || role.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Role filter functionality
            document.getElementById('roleFilter').addEventListener('change', function(e) {
                const selectedRole = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.staff-table tbody tr');
                
                rows.forEach(row => {
                    const roleCell = row.cells[2].textContent.toLowerCase();
                    
                    if (selectedRole === '' || roleCell.includes(selectedRole)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Add staff form submission
            document.getElementById('addStaffForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const name = document.getElementById('staffName').value;
                const discord = document.getElementById('discordTag').value;
                const role = document.getElementById('staffRole').value;
                const joinDate = document.getElementById('joinDate').value;
                const notes = document.getElementById('staffNotes').value;
                
                // Here you would typically send the data to your backend
                console.log('Adding staff member:', { name, discord, role, joinDate, notes });
                
                // Show success message and close modal
                alert('Staff member added successfully!');
                bootstrap.Modal.getInstance(document.getElementById('addStaffModal')).hide();
                
                // Reset form
                this.reset();
            });

            // Edit staff form submission
            document.getElementById('editStaffForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form values
                const staffId = document.getElementById('editStaffId').value;
                const name = document.getElementById('editStaffName').value;
                const discord = document.getElementById('editDiscordTag').value;
                const role = document.getElementById('editStaffRole').value;
                const joinDate = document.getElementById('editJoinDate').value;
                const status = document.getElementById('editStaffStatus').value;
                const notes = document.getElementById('editStaffNotes').value;
                
                // Here you would typically send the updated data to your backend
                console.log('Updating staff member:', { staffId, name, discord, role, joinDate, status, notes });
                
                // Show success message and close modal
                alert('Staff member updated successfully!');
                bootstrap.Modal.getInstance(document.getElementById('editStaffModal')).hide();
                
                // Update the table row with new data
                updateTableRow(staffId, { name, discord, role, status });
            });

            // Function to open edit modal
            function openEditModal(button) {
                const row = button.closest('tr');
                const cells = row.cells;
                
                // Extract data from table row
                const name = cells[0].querySelector('span').textContent;
                const discord = cells[1].textContent;
                const role = cells[2].textContent.toLowerCase().replace(/\s+/g, '');
                const joinDate = cells[3].textContent;
                const actionsCount = cells[5].textContent;
                const status = cells[6].textContent.toLowerCase();
                
                // Fill the edit form
                document.getElementById('editStaffId').value = row.dataset.staffId || Date.now(); // Use data-staff-id or generate temp ID
                document.getElementById('editStaffName').value = name;
                document.getElementById('editDiscordTag').value = discord;
                document.getElementById('editStaffRole').value = getCleanRole(role);
                document.getElementById('editJoinDate').value = convertDateFormat(joinDate);
                document.getElementById('editActionsCount').value = actionsCount;
                document.getElementById('editStaffStatus').value = status;
                
                // Show modal
                new bootstrap.Modal(document.getElementById('editStaffModal')).show();
            }

            // Function to open delete modal
            function openDeleteModal(button) {
                const row = button.closest('tr');
                const cells = row.cells;
                
                // Extract data from table row
                const name = cells[0].querySelector('span').textContent;
                const discord = cells[1].textContent;
                const role = cells[2].textContent;
                const actions = cells[5].textContent;
                
                // Fill the delete confirmation details
                document.getElementById('deleteStaffId').value = row.dataset.staffId || Date.now();
                document.getElementById('deleteStaffName').textContent = name;
                document.getElementById('deleteStaffDiscord').textContent = discord;
                document.getElementById('deleteStaffRole').textContent = role;
                document.getElementById('deleteStaffActions').textContent = actions;
                
                // Show modal
                new bootstrap.Modal(document.getElementById('deleteStaffModal')).show();
            }

            // Confirm delete functionality
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                const staffId = document.getElementById('deleteStaffId').value;
                const staffName = document.getElementById('deleteStaffName').textContent;
                
                // Here you would typically send the delete request to your backend
                console.log('Deleting staff member:', { staffId, staffName });
                
                // Find and remove the row
                const rows = document.querySelectorAll('.staff-table tbody tr');
                rows.forEach(row => {
                    const rowStaffId = row.dataset.staffId || row.cells[0].querySelector('span').textContent;
                    if (rowStaffId === staffId || row.cells[0].querySelector('span').textContent === staffName) {
                        row.style.animation = 'fadeOut 0.3s ease-out';
                        setTimeout(() => {
                            row.remove();
                            updateStaffCounts();
                        }, 300);
                    }
                });
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('deleteStaffModal')).hide();
                
                // Show success message
                alert('Staff member deleted successfully!');
            });

            // Helper functions
            function getCleanRole(roleText) {
                if (roleText.includes('admin')) return 'admin';
                if (roleText.includes('moderator')) return 'moderator';
                if (roleText.includes('helper')) return 'helper';
                return '';
            }

            function convertDateFormat(dateStr) {
                // Convert "2023-06-01" format to date input format
                return dateStr; // Assuming it's already in correct format
            }

            function updateTableRow(staffId, data) {
                // Update the table row with new data
                const rows = document.querySelectorAll('.staff-table tbody tr');
                rows.forEach(row => {
                    if ((row.dataset.staffId || row.cells[0].querySelector('span').textContent) === staffId) {
                        row.cells[0].querySelector('span').textContent = data.name;
                        row.cells[1].textContent = data.discord;
                        
                        // Update role badge
                        const roleBadge = row.cells[2].querySelector('.role-badge');
                        roleBadge.className = `role-badge role-${data.role}`;
                        roleBadge.innerHTML = `<i class="fas fa-${getRoleIcon(data.role)} me-1"></i>${data.role.charAt(0).toUpperCase() + data.role.slice(1)}`;
                        
                        // Update status badge
                        const statusBadge = row.cells[6].querySelector('.status-badge');
                        statusBadge.className = `status-badge status-${data.status}`;
                        statusBadge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                    }
                });
            }

            function getRoleIcon(role) {
                switch(role) {
                    case 'admin': return 'crown';
                    case 'moderator': return 'shield-alt';
                    case 'helper': return 'hands-helping';
                    default: return 'user';
                }
            }

            function updateStaffCounts() {
                // Update the statistics cards
                const rows = document.querySelectorAll('.staff-table tbody tr');
                let adminCount = 0, modCount = 0, helperCount = 0, activeCount = 0;
                
                rows.forEach(row => {
                    const role = row.cells[2].textContent.toLowerCase();
                    const status = row.cells[6].textContent.toLowerCase();
                    
                    if (role.includes('admin')) adminCount++;
                    if (role.includes('moderator')) modCount++;
                    if (role.includes('helper')) helperCount++;
                    if (status.includes('active')) activeCount++;
                });
                
                // Update the statistics cards
                document.querySelector('.stat-card:nth-child(1) .stat-number').textContent = adminCount;
                document.querySelector('.stat-card:nth-child(2) .stat-number').textContent = modCount;
                document.querySelector('.stat-card:nth-child(3) .stat-number').textContent = helperCount;
                document.querySelector('.stat-card:nth-child(4) .stat-number').textContent = activeCount;
            }
        </script>
    </body>
</html>
