<?php
include_once __DIR__.'/../session.php';
include_once __DIR__.'/../../backend/utils.php';
include_once __DIR__.'/../../backend/public_utils.php';

include_once __DIR__.'/../../frontend/restrictedpage.php';

// Check if user is logged in and is admin
if (!isSessionValid()) {
    header("Location: ../login.php");
    exit();
}

$user_type = getUserType();
if ($user_type !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$title = "User Management - I.T.T. Group of Education";

ob_start();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Admin Panel</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-users me-2"></i>User Management
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="searchUsers" class="form-label">Search Users</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchUsers" placeholder="Search by name, email, or phone...">
                                    <button class="btn btn-outline-primary" type="button" id="searchBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filterUserType" class="form-label">Filter by Type</label>
                                <select class="form-select" id="filterUserType">
                                    <option value="">All User Types</option>
                                    <option value="student">Students</option>
                                    <option value="admin">Admins</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filterClass" class="form-label">Filter by Class</label>
                                <select class="form-select" id="filterClass">
                                    <option value="">All Classes</option>
                                    <?php
                                    try {
                                        include '../../backend/db.php';
                                        $stmt = $pdo->prepare("SELECT ID, NAME FROM classes ORDER BY NAME");
                                        $stmt->execute();
                                        $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($classes as $class):
                                    ?>
                                        <option value="<?= $class['ID'] ?>"><?= htmlspecialchars($class['NAME']) ?></option>
                                    <?php 
                                        endforeach;
                                    } catch (PDOException $e) {
                                        echo '<option value="">Error loading classes</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filterVerified" class="form-label">Filter by Status</label>
                                <select class="form-select" id="filterVerified">
                                    <option value="">All Status</option>
                                    <option value="1">Verified</option>
                                    <option value="0">Unverified</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Total Users</h5>
                                            <h3 id="totalUsers">-</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Students</h5>
                                            <h3 id="totalStudents">-</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-user-graduate fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Verified</h5>
                                            <h3 id="totalVerified">-</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Unverified</h5>
                                            <h3 id="totalUnverified">-</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <button class="btn btn-primary" id="refreshUsers">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                            <button class="btn btn-success" id="exportUsers">
                                <i class="fas fa-file-export me-1"></i>Export to CSV
                            </button>
                            <button class="btn btn-warning" id="bulkVerify" disabled>
                                <i class="fas fa-check-double me-1"></i>Bulk Verify Selected
                            </button>
                            <button class="btn btn-danger" id="bulkDelete" disabled>
                                <i class="fas fa-trash me-1"></i>Delete Selected
                            </button>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="usersTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th>Photo</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Class</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <!-- Users will be loaded here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Users pagination">
                        <ul class="pagination justify-content-center" id="usersPagination">
                            <!-- Pagination will be generated here -->
                        </ul>
                    </nav>

                    <!-- Loading indicator -->
                    <div class="position-relative">
                        <div class="position-absolute top-0 end-0 d-none" id="loadingIndicator" style="z-index: 10;">
                            <div class="d-flex align-items-center bg-light border rounded px-3 py-2 shadow-sm">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <small class="text-muted">Updating...</small>
                            </div>
                        </div>
                    </div>

                    <!-- Skeleton loader -->
                    <div class="d-none" id="skeletonLoader">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><div class="skeleton skeleton-checkbox"></div></td>
                                        <td><div class="skeleton skeleton-avatar"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-badge"></div></td>
                                        <td><div class="skeleton skeleton-badge"></div></td>
                                        <td><div class="skeleton skeleton-text-sm"></div></td>
                                        <td><div class="skeleton skeleton-buttons"></div></td>
                                    </tr>
                                    <tr>
                                        <td><div class="skeleton skeleton-checkbox"></div></td>
                                        <td><div class="skeleton skeleton-avatar"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-badge"></div></td>
                                        <td><div class="skeleton skeleton-badge"></div></td>
                                        <td><div class="skeleton skeleton-text-sm"></div></td>
                                        <td><div class="skeleton skeleton-buttons"></div></td>
                                    </tr>
                                    <tr>
                                        <td><div class="skeleton skeleton-checkbox"></div></td>
                                        <td><div class="skeleton skeleton-avatar"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-text"></div></td>
                                        <td><div class="skeleton skeleton-badge"></div></td>
                                        <td><div class="skeleton skeleton-badge"></div></td>
                                        <td><div class="skeleton skeleton-text-sm"></div></td>
                                        <td><div class="skeleton skeleton-buttons"></div></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="userDetailsModalLabel">
                    <i class="fas fa-user me-2"></i>User Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailsContent">
                <!-- User details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="toggleVerification">Toggle Verification</button>
                <button type="button" class="btn btn-danger" id="deleteUser">Delete User</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="confirmationModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmation Required
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="confirmationMessage">
                <!-- Confirmation message will be shown here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmAction">Confirm</button>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.btn {
    border-radius: 5px;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 1rem;
}

.breadcrumb-item a {
    color: #007bff;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

.user-photo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    background-color: #f8f9fa;
    transition: opacity 0.3s ease;
}

.user-photo:not([src]), .user-photo[src=""] {
    opacity: 0;
}

.user-photo[src]:not([src=""]) {
    opacity: 1;
}

.status-badge {
    font-size: 0.8em;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.9em;
}

.table td {
    vertical-align: middle;
    font-size: 0.9em;
}

.pagination .page-link {
    color: #007bff;
}

.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

/* Smooth transitions for loading states */
#usersTable {
    transition: opacity 0.2s ease-in-out;
}

/* Prevent table layout shift during loading */
#usersTable {
    table-layout: fixed;
    width: 100%;
}

.table td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
}

.table th:nth-child(1), .table td:nth-child(1) { width: 40px; } /* Checkbox */
.table th:nth-child(2), .table td:nth-child(2) { width: 60px; } /* Photo */
.table th:nth-child(3), .table td:nth-child(3) { width: 200px; } /* Name */
.table th:nth-child(4), .table td:nth-child(4) { width: 180px; } /* Email */
.table th:nth-child(5), .table td:nth-child(5) { width: 120px; } /* Phone */
.table th:nth-child(6), .table td:nth-child(6) { width: 100px; } /* Class */
.table th:nth-child(7), .table td:nth-child(7) { width: 80px; } /* Type */
.table th:nth-child(8), .table td:nth-child(8) { width: 90px; } /* Status */
.table th:nth-child(9), .table td:nth-child(9) { width: 100px; } /* Registered */
.table th:nth-child(10), .table td:nth-child(10) { width: 120px; } /* Actions */

/* Smooth hover effects */
.table tbody tr {
    transition: background-color 0.15s ease-in-out;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.btn {
    transition: all 0.15s ease-in-out;
}

/* Skeleton loader styles */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
    border-radius: 4px;
}

@keyframes skeleton-loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

.skeleton-checkbox {
    width: 16px;
    height: 16px;
    border-radius: 3px;
}

.skeleton-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.skeleton-text {
    height: 16px;
    width: 80%;
    margin: 4px 0;
}

.skeleton-text-sm {
    height: 12px;
    width: 60%;
    margin: 4px 0;
}

.skeleton-badge {
    height: 20px;
    width: 60px;
    border-radius: 10px;
}

.skeleton-buttons {
    height: 32px;
    width: 100px;
    border-radius: 4px;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem 0.5rem;
    }
    
    .btn {
        font-size: 0.8rem;
        padding: 0.375rem 0.5rem;
    }
    
    .table {
        font-size: 0.8rem;
    }
}
</style>

<script>
let currentPage = 1;
let usersPerPage = 10;
let totalUsers = 0;
let selectedUsers = new Set();
let currentUserForAction = null;
let searchTimeout = null;
let lastUsersData = null; // Cache for preventing unnecessary updates
let userRowCache = new Map(); // Cache individual user row data

document.addEventListener('DOMContentLoaded', function() {
    // Load users on page load
    loadUsers();
    
    // Setup event listeners
    setupEventListeners();
    
    // Load statistics
    loadUserStatistics();
});

function setupEventListeners() {
    // Search functionality with debouncing
    document.getElementById('searchUsers').addEventListener('keyup', function(e) {
        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        if (e.key === 'Enter') {
            currentPage = 1;
            loadUsers();
        } else {
            // Debounce search - wait 300ms after user stops typing
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                loadUsers();
            }, 300);
        }
    });
    
    document.getElementById('searchBtn').addEventListener('click', function() {
        currentPage = 1;
        loadUsers();
    });
    
    // Filter functionality
    document.getElementById('filterUserType').addEventListener('change', function() {
        currentPage = 1;
        loadUsers();
    });
    
    document.getElementById('filterClass').addEventListener('change', function() {
        currentPage = 1;
        loadUsers();
    });
    
    document.getElementById('filterVerified').addEventListener('change', function() {
        currentPage = 1;
        loadUsers();
    });
    
    // Action buttons
    document.getElementById('refreshUsers').addEventListener('click', loadUsers);
    document.getElementById('exportUsers').addEventListener('click', exportUsers);
    document.getElementById('bulkVerify').addEventListener('click', bulkVerifyUsers);
    document.getElementById('bulkDelete').addEventListener('click', bulkDeleteUsers);
    
    // Select all checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="userSelect"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            if (this.checked) {
                selectedUsers.add(checkbox.value);
            } else {
                selectedUsers.delete(checkbox.value);
            }
        });
        updateBulkActionButtons();
    });
    
    // Modal action buttons
    document.getElementById('toggleVerification').addEventListener('click', toggleUserVerification);
    document.getElementById('deleteUser').addEventListener('click', function() {
        showConfirmation('Are you sure you want to delete this user? This action cannot be undone.', 'deleteUser');
    });
    
    document.getElementById('confirmAction').addEventListener('click', executeConfirmedAction);
}

function loadUsers() {
    showLoading(true);
    
    const searchTerm = document.getElementById('searchUsers').value;
    const userType = document.getElementById('filterUserType').value;
    const classFilter = document.getElementById('filterClass').value;
    const verifiedFilter = document.getElementById('filterVerified').value;
    
    const params = new URLSearchParams({
        action: 'get_users',
        page: currentPage,
        limit: usersPerPage,
        search: searchTerm,
        user_type: userType,
        class: classFilter,
        verified: verifiedFilter
    });
    
    fetch('../../backend/manage_users.php?' + params.toString())
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Only update if data has actually changed
                const usersDataString = JSON.stringify(data.users);
                if (lastUsersData !== usersDataString) {
                    displayUsers(data.users);
                    lastUsersData = usersDataString;
                }
                totalUsers = data.total;
                updatePagination(data.total);
                updateUserStatistics(data.statistics);
            } else {
                showMessage('Error loading users: ' + (data.error || 'Unknown error'), 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error loading users: ' + error.message, 'danger');
        })
        .finally(() => {
            showLoading(false);
        });
}

function displayUsers(users) {
    const tbody = document.getElementById('usersTableBody');
    const existingRows = Array.from(tbody.querySelectorAll('tr[data-user-id]'));
    
    if (users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted py-4">No users found</td></tr>';
        return;
    }
    
    // Create a map of existing rows by user ID for quick lookup
    const existingRowsMap = new Map();
    existingRows.forEach(row => {
        const userId = row.getAttribute('data-user-id');
        if (userId) {
            existingRowsMap.set(userId, row);
        }
    });
    
    // Create a map of new users by ID
    const newUsersMap = new Map();
    users.forEach(user => {
        newUsersMap.set(user.id.toString(), user);
    });
    
    // Remove rows that are no longer needed
    existingRows.forEach(row => {
        const userId = row.getAttribute('data-user-id');
        if (!newUsersMap.has(userId)) {
            row.style.opacity = '0';
            setTimeout(() => {
                if (row.parentNode) {
                    row.remove();
                }
            }, 150);
        }
    });
    
    // Update existing rows and add new ones
    const fragment = document.createDocumentFragment();
    let insertPosition = 0;
    
    users.forEach((user, index) => {
        const userId = user.id.toString();
        const existingRow = existingRowsMap.get(userId);
        
        if (existingRow) {
            // Update existing row in place
            updateRowContent(existingRow, user);
            
            // Move row to correct position if needed
            const currentPosition = Array.from(tbody.children).indexOf(existingRow);
            if (currentPosition !== index) {
                if (index < tbody.children.length) {
                    tbody.insertBefore(existingRow, tbody.children[index]);
                } else {
                    tbody.appendChild(existingRow);
                }
            }
        } else {
            // Create new row
            const newRow = createUserRow(user);
            
            // Add fade-in effect
            newRow.style.opacity = '0';
            setTimeout(() => {
                newRow.style.transition = 'opacity 0.3s ease-in-out';
                newRow.style.opacity = '1';
            }, 50);
            
            // Insert at correct position
            if (index < tbody.children.length) {
                tbody.insertBefore(newRow, tbody.children[index]);
            } else {
                tbody.appendChild(newRow);
            }
        }
    });
}

function createUserRow(user) {
    const row = document.createElement('tr');
    row.setAttribute('data-user-id', user.id);
    row.innerHTML = getUserRowHTML(user);
    return row;
}

function updateRowContent(row, user) {
    const userId = user.id.toString();
    
    // Check if this specific user's data has changed
    const cachedUserData = userRowCache.get(userId);
    const currentUserData = JSON.stringify(user);
    
    if (cachedUserData === currentUserData) {
        // Data hasn't changed, only update checkbox state
        const checkbox = row.querySelector('input[type="checkbox"]');
        checkbox.checked = selectedUsers.has(userId);
        return; // Exit early to avoid any DOM manipulation
    }
    
    // Cache the new data
    userRowCache.set(userId, currentUserData);
    
    // Update cells individually to avoid image reloading
    const cells = row.querySelectorAll('td');
    
    // Update checkbox (cell 0)
    const checkbox = cells[0].querySelector('input[type="checkbox"]');
    checkbox.checked = selectedUsers.has(userId);
    
    // Skip photo update completely to avoid blinking
    // Photo will only be set during initial row creation
    
    // Update name (cell 2)
    const nameCell = cells[2];
    const newNameHTML = `<strong>${escapeHtml(user.full_name)}</strong><br><small class="text-muted">${escapeHtml(user.father_name || '')}</small>`;
    if (nameCell.innerHTML !== newNameHTML) {
        nameCell.innerHTML = newNameHTML;
    }
    
    // Update email (cell 3)
    if (cells[3].textContent !== user.email) {
        cells[3].textContent = user.email;
    }
    
    // Update phone (cell 4)
    if (cells[4].textContent !== user.phone) {
        cells[4].textContent = user.phone;
    }
    
    // Update class (cell 5)
    const className = user.class_name || 'N/A';
    if (cells[5].textContent !== className) {
        cells[5].textContent = className;
    }
    
    // Update user type badge (cell 6)
    const userTypeBadge = cells[6].querySelector('.badge');
    const userTypeText = user.user_type.charAt(0).toUpperCase() + user.user_type.slice(1);
    const userTypeBadgeClass = user.user_type === 'admin' ? 'bg-danger' : 'bg-primary';
    
    if (userTypeBadge.textContent !== userTypeText) {
        userTypeBadge.textContent = userTypeText;
        userTypeBadge.className = `badge ${userTypeBadgeClass} status-badge`;
    }
    
    // Update verification badge (cell 7)
    const verifiedBadge = cells[7].querySelector('.badge');
    const verifiedText = user.verified == '1' ? 'Verified' : 'Unverified';
    const verifiedBadgeClass = user.verified == '1' ? 'bg-success' : 'bg-warning';
    
    if (verifiedBadge.textContent !== verifiedText) {
        verifiedBadge.textContent = verifiedText;
        verifiedBadge.className = `badge ${verifiedBadgeClass} status-badge`;
    }
    
    // Update date (cell 8) - usually doesn't change but check anyway
    const dateText = formatDate(user.created_at);
    const currentDateText = cells[8].querySelector('small').textContent;
    if (currentDateText !== dateText) {
        cells[8].innerHTML = `<small class="text-muted">${dateText}</small>`;
    }
    
    // Update action buttons (cell 9) - update onclick handlers
    const actionButtons = cells[9].querySelectorAll('button');
    
    // View button
    actionButtons[0].setAttribute('onclick', `viewUserDetails(${user.id})`);
    
    // Verify/Unverify button
    const verifyBtn = actionButtons[1];
    verifyBtn.setAttribute('onclick', `toggleVerification(${user.id}, ${user.verified})`);
    verifyBtn.setAttribute('title', `${user.verified == '1' ? 'Unverify' : 'Verify'} User`);
    const verifyIcon = verifyBtn.querySelector('i');
    const newIconClass = `fas fa-${user.verified == '1' ? 'times' : 'check'}`;
    if (verifyIcon.className !== newIconClass) {
        verifyIcon.className = newIconClass;
    }
    
    // Delete button
    actionButtons[2].setAttribute('onclick', `deleteUserConfirm(${user.id})`);
}

function getUserRowHTML(user) {
    return `
        <td>
            <input type="checkbox" name="userSelect" value="${user.id}" class="form-check-input" onchange="updateSelectedUsers(this)" ${selectedUsers.has(user.id.toString()) ? 'checked' : ''}>
        </td>
        <td>
            <img src="${user.photo || '../../images/default-avatar.png'}" 
                 alt="User Photo" 
                 class="user-photo" 
                 data-expected-src="${user.photo || '../../images/default-avatar.png'}"
                 onerror="this.src='../../images/default-avatar.png'">
        </td>
        <td>
            <strong>${escapeHtml(user.full_name)}</strong>
            <br><small class="text-muted">${escapeHtml(user.father_name || '')}</small>
        </td>
        <td>${escapeHtml(user.email)}</td>
        <td>${escapeHtml(user.phone)}</td>
        <td>${escapeHtml(user.class_name || 'N/A')}</td>
        <td>
            <span class="badge ${user.user_type === 'admin' ? 'bg-danger' : 'bg-primary'} status-badge">
                ${user.user_type.charAt(0).toUpperCase() + user.user_type.slice(1)}
            </span>
        </td>
        <td>
            <span class="badge ${user.verified == '1' ? 'bg-success' : 'bg-warning'} status-badge">
                ${user.verified == '1' ? 'Verified' : 'Unverified'}
            </span>
        </td>
        <td>
            <small class="text-muted">${formatDate(user.created_at)}</small>
        </td>
        <td>
            <button class="btn btn-sm btn-outline-info" onclick="viewUserDetails(${user.id})" title="View Details">
                <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-outline-warning" onclick="toggleVerification(${user.id}, ${user.verified})" title="${user.verified == '1' ? 'Unverify' : 'Verify'} User">
                <i class="fas fa-${user.verified == '1' ? 'times' : 'check'}"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger" onclick="deleteUserConfirm(${user.id})" title="Delete User">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
}

function updateSelectedUsers(checkbox) {
    if (checkbox.checked) {
        selectedUsers.add(checkbox.value);
    } else {
        selectedUsers.delete(checkbox.value);
        document.getElementById('selectAll').checked = false;
    }
    updateBulkActionButtons();
}

function updateBulkActionButtons() {
    const hasSelected = selectedUsers.size > 0;
    document.getElementById('bulkVerify').disabled = !hasSelected;
    document.getElementById('bulkDelete').disabled = !hasSelected;
}

function updatePagination(total) {
    const totalPages = Math.ceil(total / usersPerPage);
    const pagination = document.getElementById('usersPagination');
    pagination.innerHTML = '';
    
    if (totalPages <= 1) return;
    
    // Previous button
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Previous</a>`;
    pagination.appendChild(prevLi);
    
    // Page numbers
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i})">${i}</a>`;
        pagination.appendChild(li);
    }
    
    // Next button
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a>`;
    pagination.appendChild(nextLi);
}

function changePage(page) {
    if (page < 1) return;
    currentPage = page;
    loadUsers();
}

function loadUserStatistics() {
    fetch('../../backend/manage_users.php?action=get_statistics')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateUserStatistics(data.statistics);
            }
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
        });
}

function updateUserStatistics(stats) {
    document.getElementById('totalUsers').textContent = stats.total || '0';
    document.getElementById('totalStudents').textContent = stats.students || '0';
    document.getElementById('totalVerified').textContent = stats.verified || '0';
    document.getElementById('totalUnverified').textContent = stats.unverified || '0';
}

function viewUserDetails(userId) {
    fetch(`../../backend/manage_users.php?action=get_user_details&user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayUserDetails(data.user);
                currentUserForAction = userId;
                const modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
                modal.show();
            } else {
                showMessage('Error loading user details: ' + (data.error || 'Unknown error'), 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error loading user details: ' + error.message, 'danger');
        });
}

function displayUserDetails(user) {
    const content = document.getElementById('userDetailsContent');
    content.innerHTML = `
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="${user.photo || '../../images/default-avatar.png'}" 
                     alt="User Photo" 
                     class="img-fluid rounded-circle mb-3" 
                     style="max-width: 150px; max-height: 150px; object-fit: cover;"
                     onerror="this.src='../../images/default-avatar.png'">
                <h5>${escapeHtml(user.full_name)}</h5>
                <span class="badge ${user.user_type === 'admin' ? 'bg-danger' : 'bg-primary'} mb-2">
                    ${user.user_type.charAt(0).toUpperCase() + user.user_type.slice(1)}
                </span>
                <br>
                <span class="badge ${user.verified == '1' ? 'bg-success' : 'bg-warning'}">
                    ${user.verified == '1' ? 'Verified' : 'Unverified'}
                </span>
            </div>
            <div class="col-md-8">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Full Name:</strong></td>
                        <td>${escapeHtml(user.full_name)}</td>
                    </tr>
                    <tr>
                        <td><strong>Father's Name:</strong></td>
                        <td>${escapeHtml(user.father_name || 'N/A')}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>${escapeHtml(user.email)}</td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td>${escapeHtml(user.phone)}</td>
                    </tr>
                    <tr>
                        <td><strong>Date of Birth:</strong></td>
                        <td>${user.dob ? formatDate(user.dob) : 'N/A'}</td>
                    </tr>
                    <tr>
                        <td><strong>Class:</strong></td>
                        <td>${escapeHtml(user.class_name || 'N/A')}</td>
                    </tr>
                    <tr>
                        <td><strong>User Type:</strong></td>
                        <td>${user.user_type.charAt(0).toUpperCase() + user.user_type.slice(1)}</td>
                    </tr>
                    <tr>
                        <td><strong>Account Status:</strong></td>
                        <td>
                            <span class="badge ${user.verified == '1' ? 'bg-success' : 'bg-warning'}">
                                ${user.verified == '1' ? 'Verified' : 'Unverified'}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Registered:</strong></td>
                        <td>${formatDate(user.created_at)}</td>
                    </tr>
                </table>
            </div>
        </div>
    `;
}

function toggleVerification(userId, currentStatus) {
    const newStatus = currentStatus == '1' ? '0' : '1';
    const action = newStatus == '1' ? 'verify' : 'unverify';
    
    fetch('../../backend/manage_users.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=toggle_verification&user_id=${userId}&status=${newStatus}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(`User ${action}ed successfully`, 'success');
            loadUsers();
            loadUserStatistics();
        } else {
            showMessage('Error updating user status: ' + (data.error || 'Unknown error'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error updating user status: ' + error.message, 'danger');
    });
}

function toggleUserVerification() {
    if (currentUserForAction) {
        fetch(`../../backend/manage_users.php?action=get_user_status&user_id=${currentUserForAction}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toggleVerification(currentUserForAction, data.verified);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('userDetailsModal'));
                    modal.hide();
                }
            });
    }
}

function deleteUserConfirm(userId) {
    currentUserForAction = userId;
    showConfirmation('Are you sure you want to delete this user? This action cannot be undone.', 'deleteUser');
}

function bulkVerifyUsers() {
    if (selectedUsers.size === 0) return;
    
    const userIds = Array.from(selectedUsers);
    
    fetch('../../backend/manage_users.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=bulk_verify&user_ids=${userIds.join(',')}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Users verified successfully', 'success');
            selectedUsers.clear();
            document.getElementById('selectAll').checked = false;
            updateBulkActionButtons();
            loadUsers();
            loadUserStatistics();
        } else {
            showMessage('Error verifying users: ' + (data.error || 'Unknown error'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error verifying users: ' + error.message, 'danger');
    });
}

function bulkDeleteUsers() {
    if (selectedUsers.size === 0) return;
    
    showConfirmation(`Are you sure you want to delete ${selectedUsers.size} selected user(s)? This action cannot be undone.`, 'bulkDelete');
}

function showConfirmation(message, action) {
    document.getElementById('confirmationMessage').textContent = message;
    document.getElementById('confirmAction').setAttribute('data-action', action);
    const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    modal.show();
}

function executeConfirmedAction() {
    const action = document.getElementById('confirmAction').getAttribute('data-action');
    
    if (action === 'deleteUser' && currentUserForAction) {
        deleteUser(currentUserForAction);
    } else if (action === 'bulkDelete') {
        performBulkDelete();
    }
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
    modal.hide();
}

function deleteUser(userId) {
    fetch('../../backend/manage_users.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=delete_user&user_id=${userId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('User deleted successfully', 'success');
            loadUsers();
            loadUserStatistics();
            
            // Close user details modal if open
            const detailsModal = bootstrap.Modal.getInstance(document.getElementById('userDetailsModal'));
            if (detailsModal) {
                detailsModal.hide();
            }
        } else {
            showMessage('Error deleting user: ' + (data.error || 'Unknown error'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error deleting user: ' + error.message, 'danger');
    });
}

function performBulkDelete() {
    if (selectedUsers.size === 0) return;
    
    const userIds = Array.from(selectedUsers);
    
    fetch('../../backend/manage_users.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=bulk_delete&user_ids=${userIds.join(',')}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Users deleted successfully', 'success');
            selectedUsers.clear();
            document.getElementById('selectAll').checked = false;
            updateBulkActionButtons();
            loadUsers();
            loadUserStatistics();
        } else {
            showMessage('Error deleting users: ' + (data.error || 'Unknown error'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error deleting users: ' + error.message, 'danger');
    });
}

function exportUsers() {
    const searchTerm = document.getElementById('searchUsers').value;
    const userType = document.getElementById('filterUserType').value;
    const classFilter = document.getElementById('filterClass').value;
    const verifiedFilter = document.getElementById('filterVerified').value;
    
    const params = new URLSearchParams({
        action: 'export_users',
        search: searchTerm,
        user_type: userType,
        class: classFilter,
        verified: verifiedFilter
    });
    
    // Create a temporary link to download the CSV
    const link = document.createElement('a');
    link.href = '../../backend/manage_users.php?' + params.toString();
    link.download = 'users_export.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    showMessage('Export started. Download should begin shortly.', 'info');
}

function showLoading(show) {
    const loadingIndicator = document.getElementById('loadingIndicator');
    const skeletonLoader = document.getElementById('skeletonLoader');
    const usersTable = document.getElementById('usersTable');
    const tbody = document.getElementById('usersTableBody');
    
    if (show) {
        // Only show skeleton for completely empty table
        if (tbody.children.length === 0 || tbody.innerHTML.includes('No users found')) {
            skeletonLoader.classList.remove('d-none');
            usersTable.classList.add('d-none');
        } else {
            // For existing data, just show a subtle loading indicator
            // Don't change table opacity to avoid flickering
            loadingIndicator.classList.remove('d-none');
        }
    } else {
        loadingIndicator.classList.add('d-none');
        skeletonLoader.classList.add('d-none');
        usersTable.classList.remove('d-none');
        usersTable.style.opacity = '1';
        usersTable.style.pointerEvents = 'auto';
    }
}

function showMessage(message, type) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text ? text.replace(/[&<>"']/g, m => map[m]) : '';
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}
</script>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php
$content = ob_get_clean();
include_once __DIR__.'/../master.php';
?>
