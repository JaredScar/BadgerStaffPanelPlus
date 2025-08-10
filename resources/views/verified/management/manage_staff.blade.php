<?php
function getRoleIcon($role) {
    switch($role) {
        case 'admin': return 'crown';
        case 'moderator': return 'shield-alt';
        case 'helper': return 'hands-helping';
        default: return 'user';
    }
}
?>
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
                                <h3 class="stat-number">{{ $data['staff_statistics']['admins'] ?? 0 }}</h3>
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
                                <h3 class="stat-number">{{ $data['staff_statistics']['moderators'] ?? 0 }}</h3>
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
                                <h3 class="stat-number">{{ $data['staff_statistics']['helpers'] ?? 0 }}</h3>
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
                                <h3 class="stat-number">{{ $data['staff_statistics']['active_staff'] ?? 0 }}</h3>
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
                    <h2>Staff Members ({{ $data['staff_statistics']['total_staff'] ?? 0 }})</h2>
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
                        <tbody id="staffTableBody">
                            @foreach($data['staff_members'] as $staff)
                            <tr data-staff-id="{{ $staff['staff_id'] }}">
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span>{{ $staff['staff_username'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="discord-tag">{{ $staff['staff_discord'] }}</span>
                                </td>
                                <td>
                                    <span class="role-badge role-{{ $staff['role'] ?? 'helper' }}">
                                        <i class="fas fa-{{ getRoleIcon($staff['role'] ?? 'helper') }} me-1"></i>
                                        {{ ucfirst($staff['role'] ?? 'helper') }}
                                    </span>
                                </td>
                                <td>{{ $staff['join_date'] ? \Carbon\Carbon::parse($staff['join_date'])->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    @if($staff['last_active'])
                                        <span class="status-online">{{ \Carbon\Carbon::parse($staff['last_active'])->diffForHumans() }}</span>
                                    @else
                                        <span class="status-offline">Never</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="actions-count">{{ $staff['total_actions'] ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $staff['status'] ?? 'active' }}">{{ ucfirst($staff['status'] ?? 'active') }}</span>
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
                            @endforeach
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
                            <label for="staffName" class="form-label">Username</label>
                            <input type="text" class="form-control" id="staffName" name="staff_username" required>
                        </div>
                        <div class="col-md-6">
                            <label for="staffEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="staffEmail" name="staff_email" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="discordTag" class="form-label">Discord Tag</label>
                            <input type="text" class="form-control" id="discordTag" name="staff_discord" placeholder="Username#0000" required>
                        </div>
                        <div class="col-md-6">
                            <label for="staffPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="staffPassword" name="password" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="staffRole" class="form-label">Role</label>
                            <select class="form-select" id="staffRole" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="moderator">Moderator</option>
                                <option value="helper">Helper</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="joinDate" class="form-label">Join Date</label>
                            <input type="date" class="form-control" id="joinDate" name="join_date">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="staffNotes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="staffNotes" name="notes" rows="3" placeholder="Additional notes about this staff member..."></textarea>
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
                            <label for="editStaffName" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editStaffName" name="staff_username" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editStaffEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editStaffEmail" name="staff_email" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editDiscordTag" class="form-label">Discord Tag</label>
                            <input type="text" class="form-control" id="editDiscordTag" name="staff_discord" placeholder="Username#0000" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editStaffPassword" class="form-label">Password (Leave blank to keep current)</label>
                            <input type="password" class="form-control" id="editStaffPassword" name="password">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editStaffRole" class="form-label">Role</label>
                            <select class="form-select" id="editStaffRole" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="moderator">Moderator</option>
                                <option value="helper">Helper</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="editStaffStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStaffStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editJoinDate" class="form-label">Join Date</label>
                            <input type="date" class="form-control" id="editJoinDate" name="join_date">
                        </div>
                        <div class="col-md-6">
                            <label for="editActionsCount" class="form-label">Actions Count</label>
                            <input type="number" class="form-control" id="editActionsCount" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editStaffNotes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="editStaffNotes" name="notes" rows="3" placeholder="Additional notes about this staff member..."></textarea>
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
    // CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        fetch('/api/staff/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showToast('success', 'Staff member added successfully!');
                bootstrap.Modal.getInstance(document.getElementById('addStaffModal')).hide();
                this.reset();
                // Reload the page to show new data
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('error', result.error || 'Failed to add staff member');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'An error occurred while adding staff member');
        });
    });

    // Edit staff form submission
    document.getElementById('editStaffForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const staffId = document.getElementById('editStaffId').value;
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        fetch(`/api/staff/${staffId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showToast('success', 'Staff member updated successfully!');
                bootstrap.Modal.getInstance(document.getElementById('editStaffModal')).hide();
                // Reload the page to show updated data
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('error', result.error || 'Failed to update staff member');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'An error occurred while updating staff member');
        });
    });

    // Function to open edit modal
    function openEditModal(button) {
        const row = button.closest('tr');
        const staffId = row.dataset.staffId;
        
        // Fetch staff data
        fetch(`/api/staff/${staffId}`)
        .then(response => response.json())
        .then(staff => {
            if (staff.error) {
                showToast('error', staff.error);
                return;
            }
            
            // Fill the edit form
            document.getElementById('editStaffId').value = staff.staff_id;
            document.getElementById('editStaffName').value = staff.staff_username;
            document.getElementById('editStaffEmail').value = staff.staff_email;
            document.getElementById('editDiscordTag').value = staff.staff_discord;
            document.getElementById('editStaffRole').value = staff.role || 'helper';
            document.getElementById('editStaffStatus').value = staff.status || 'active';
            document.getElementById('editJoinDate').value = staff.join_date ? staff.join_date.split('T')[0] : '';
            document.getElementById('editActionsCount').value = staff.total_actions || 0;
            document.getElementById('editStaffNotes').value = staff.notes || '';
            
            // Show modal
            new bootstrap.Modal(document.getElementById('editStaffModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'Failed to load staff data');
        });
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
        const staffId = row.dataset.staffId;
        
        // Fill the delete confirmation details
        document.getElementById('deleteStaffId').value = staffId;
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
        
        fetch(`/api/staff/${staffId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showToast('success', 'Staff member deleted successfully!');
                bootstrap.Modal.getInstance(document.getElementById('deleteStaffModal')).hide();
                
                // Remove the row with animation
                const row = document.querySelector(`tr[data-staff-id="${staffId}"]`);
                if (row) {
                    row.style.animation = 'fadeOut 0.3s ease-out';
                    setTimeout(() => {
                        row.remove();
                        updateStaffCounts();
                    }, 300);
                }
            } else {
                showToast('error', result.error || 'Failed to delete staff member');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'An error occurred while deleting staff member');
        });
    });

    // Helper functions
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
        
        // Update total count
        document.querySelector('.section-header h2').textContent = `Staff Members (${rows.length})`;
    }

    // Toast notification function
    function showToast(type, message) {
        // You can implement your own toast notification here
        // For now, using alert as a fallback
        alert(message);
    }
</script>

<style>
    @keyframes fadeOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(-100%); }
    }
</style>
</body>
</html>
