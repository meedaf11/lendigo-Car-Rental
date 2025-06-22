<?php
require_once 'functions.php';

$statusFilter = $_GET['status'] ?? '';

$messages = getMessages($statusFilter);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Messages</title>
    <link rel="stylesheet" href="css/messages.css">
</head>
<body>
    <div class="message-container">

        <!-- Filter Section -->
        <div class="filter-bar">
            <label for="status">Filter by Status:</label>
            <select id="status" onchange="filterByStatus(this.value)">
                <option value="">All</option>
                <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="answered" <?= $statusFilter === 'answered' ? 'selected' : '' ?>>Answered</option>
            </select>
        </div>

        <!-- Message Table -->
        <table class="message-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Submitted At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($messages) > 0): ?>
                    <?php foreach ($messages as $msg): ?>
                        <tr onclick="openMessageModal(<?= htmlspecialchars(json_encode($msg), ENT_QUOTES, 'UTF-8') ?>)">
                            <td><?= $msg['user_id'] ?></td>
                            <td><?= htmlspecialchars($msg['full_name']) ?></td>
                            <td><?= htmlspecialchars($msg['email']) ?></td>
                            <td><?= $msg['submitted_at'] ?></td>
                            <td><?= ucfirst($msg['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;">No messages found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="messageModal" class="modal hidden">
        <div class="modal-content">
            <span class="close-btn" onclick="closeMessageModal()">×</span>
            <div id="modalDetails">
                <!-- Filled dynamically -->
            </div>
        </div>
    </div>

    <script>
        function filterByStatus(status) {
            const url = new URL(window.location.href);
            url.searchParams.set('status', status);
            window.location.href = url.toString();
        }

        function openMessageModal(message) {
            const modal = document.getElementById('messageModal');
            const details = document.getElementById('modalDetails');

            details.innerHTML = `
                <h2>📩 ${message.subject}</h2>
                <p><strong>From:</strong> ${message.full_name} (${message.email})</p>
                <p><strong>Submitted:</strong> ${message.submitted_at}</p>
                <p><strong>Message:</strong><br>${message.message}</p>

                <h3>📝 Answer</h3>
                <textarea id="answerInput" rows="5" placeholder="Type your answer here...">${message.answer ?? ''}</textarea>

                <div style="margin-top: 1rem;">
                    <label for="statusSelect">Change Status:</label>
                    <select id="statusSelect">
                        <option value="pending" ${message.status === 'pending' ? 'selected' : ''}>Pending</option>
                        <option value="answered" ${message.status === 'answered' ? 'selected' : ''}>Answered</option>
                    </select>
                </div>

                <button onclick="submitAnswer(${message.id})" style="margin-top: 1rem;">✅ Confirm</button>
                <div id="responseMsg" style="margin-top:10px;"></div>
            `;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMessageModal() {
            document.getElementById('messageModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function submitAnswer(messageId) {
            const answer = document.getElementById('answerInput').value.trim();
            const newStatus = document.getElementById('statusSelect').value;
            const responseMsg = document.getElementById('responseMsg');

            if (newStatus === 'answered' && answer === '') {
                responseMsg.innerText = "❗ Cannot mark as answered without an answer.";
                responseMsg.style.color = 'red';
                return;
            }

            fetch('update_message.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ id: messageId, answer: answer, status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    responseMsg.innerText = "✅ Message updated.";
                    responseMsg.style.color = 'green';
                    setTimeout(() => location.reload(), 1200);
                } else {
                    responseMsg.innerText = "❌ Failed to update message.";
                    responseMsg.style.color = 'red';
                }
            })
            .catch(() => {
                responseMsg.innerText = "❗ An error occurred.";
                responseMsg.style.color = 'red';
            });
        }
    </script>
</body>
</html>
