<?php
require_once 'functions.php';

$statusFilter = $_GET['status'] ?? null;
$users = getUsersWithAgencyCount($statusFilter);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Dashboard</title>
    <link rel="stylesheet" href="css/users.css">
    <style>
       
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1 class="stats-header">User Management</h1>
            <p class="header-subtitle">Manage and monitor all user accounts and their associated agencies</p>
        </div>

        <!-- Controls Section -->
        <div class="controls-section">
            <div class="filter-container">
                <label for="status-filter" class="filter-label">Filter by Status:</label>
                <select id="status-filter" class="filter-select">
                    <option value="">All Users</option>
                    <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active Users</option>
                    <option value="blocked" <?= $statusFilter === 'blocked' ? 'selected' : '' ?>>Blocked Users</option>
                </select>
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Agencies</th>
                        <th>Actions</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr id="user-row-<?= $user['user_id'] ?>">
                            <td>
                                <span class="user-id"><?= $user['user_id'] ?></span>
                            </td>
                            <td>
                                <span class="username"><?= htmlspecialchars($user['username']) ?></span>
                            </td>
                            <td>
                                <span class="email"><?= htmlspecialchars($user['email']) ?></span>
                            </td>
                            <td>
                                <span class="phone"><?= htmlspecialchars($user['phone_number']) ?></span>
                            </td>
                            <td>
                                <span class="agency-count"><?= $user['agency_count'] ?></span>
                            </td>
                            <td>
                                <button class="view-btn" onclick="viewUserDetails(<?= $user['user_id'] ?>)">
                                    🔍 View Details
                                </button>
                            </td>
                            <td>
                                <select class="status-select" onchange="updateStatus(<?= $user['user_id'] ?>, this.value)">
                                    <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="blocked" <?= $user['status'] === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="user-modal" class="modal">
        <div class="modal-backdrop" onclick="closeModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">👤 User Details</h3>
            </div>
            <div id="user-details-content"></div>
            <button class="modal-close" onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        // Filter functionality
        document.getElementById('status-filter').addEventListener('change', function () {
            const status = this.value;
            this.classList.add('loading');
            
            setTimeout(() => {
                window.location.href = 'index.php?page=users' + (status ? '&status=' + status : '');
            }, 300);
        });

        // Update user status
        function updateStatus(userId, newStatus) {
            const row = document.getElementById(`user-row-${userId}`);
            const select = row.querySelector('.status-select');
            
            select.disabled = true;
            row.classList.add('loading');

            fetch('users.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `user_id=${userId}&new_status=${newStatus}`
            })
            .then(res => res.json())
            .then(data => {
                select.disabled = false;
                row.classList.remove('loading');
                
                if (data.success) {
                    row.classList.add('status-updated');
                    setTimeout(() => row.classList.remove('status-updated'), 2000);
                } else {
                    row.classList.add('status-error');
                    setTimeout(() => row.classList.remove('status-error'), 2000);
                    alert('Failed to update status. Please try again.');
                }
            })
            .catch(error => {
                select.disabled = false;
                row.classList.remove('loading');
                row.classList.add('status-error');
                setTimeout(() => row.classList.remove('status-error'), 2000);
                alert('An error occurred. Please try again.');
            });
        }

        // View user details
        function viewUserDetails(userId) {
            const modal = document.getElementById('user-modal');
            const content = document.getElementById('user-details-content');
            
            content.innerHTML = '<div style="text-align: center; padding: 40px; color: var(--text-muted);">Loading user details...</div>';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';

            fetch('user_details.php?user_id=' + userId)
                .then(res => res.text())
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(error => {
                    content.innerHTML = '<div style="text-align: center; padding: 40px; color: var(--primary-color);">Error loading user details. Please try again.</div>';
                });
        }

        // Close modal
        function closeModal() {
            const modal = document.getElementById('user-modal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Enhanced table interactions
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>

</html>