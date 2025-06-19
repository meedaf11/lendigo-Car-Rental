<?php
require_once __DIR__ . '/../functions.php';

$agency_id = $_SESSION['agency_id'] ?? null;

if (!$agency_id) {
    die("Agency not found.");
}

// جلب بيانات الوكالة
$agency = getAgencyById($agency_id, $pdo);

// عند تحديث البيانات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_agency'])) {
    $updateData = [
        'agency_id' => $agency_id,
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'image' => $_POST['image'],
        'contact_email' => $_POST['contact_email'],
        'phone_number' => $_POST['phone_number'],
        'agency_city' => $_POST['agency_city'],
        'location' => $_POST['location'],
    ];

    if (updateAgency($updateData, $pdo)) {
        echo "<script>alert('✅ Agency updated successfully');</script>";
        $agency = getAgencyById($agency_id, $pdo); // تحديث البيانات المعروضة
    } else {
        echo "<script>alert('❌ Failed to update agency');</script>";
    }
}

// عند الحذف
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    if (deleteAgencyById($agency_id, $pdo)) {
        session_destroy();
        header("Location: ../../../index.php?message=agencyDeleted");
        exit;
    } else {
        echo "<script>alert('❌ Failed to delete agency');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agency Settings</title>
    <link rel="stylesheet" href="css/settings.css">
</head>

<body>
    <div class="container">
        <h2 class="page-title">⚙️ Agency Settings</h2>

        <div id="alertContainer"></div>

        <form method="POST" class="settings-form" id="settingsForm">
            <div class="form-section">
                <h3 class="form-section-title">Basic Information</h3>

                <div class="form-group">
                    <label for="name">Agency Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($agency['name']) ?>" required>

                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required
                        placeholder="Describe your agency's services and specialties..."><?= htmlspecialchars($agency['description']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Agency Image URL</label>
                    <input type="url" id="image" name="image" value="<?= htmlspecialchars($agency['image']) ?>" required
                        placeholder="https://example.com/image.jpg">
                    <div class="image-preview" id="imagePreview">
                        <img src="<?= htmlspecialchars($agency['image']) ?>" alt="Agency Preview" id="previewImg">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Contact Information</h3>

                <div class="form-group">
                    <label for="contact_email">Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email"
                        value="<?= htmlspecialchars($agency['contact_email']) ?>" required
                        placeholder="agency@example.com">
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number"
                        value="<?= htmlspecialchars($agency['phone_number']) ?>" required
                        placeholder="+212 xxx xxx xxx">
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Location Details</h3>

                <div class="form-group">
                    <label for="agency_city">City</label>
                    <input type="text" id="agency_city" name="agency_city"
                        value="<?= htmlspecialchars($agency['agency_city']) ?>" required placeholder="Enter city name">
                </div>

                <div class="form-group">
                    <label for="location">Full Address</label>
                    <input type="text" id="location" name="location"
                        value="<?= htmlspecialchars($agency['location']) ?>" required
                        placeholder="Enter complete address">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="update_agency" class="btn btn-primary" id="updateBtn">
                    💾 Update Agency
                    <span class="loading-spinner" id="loadingSpinner"></span>
                </button>
                <a href="?action=delete" class="btn btn-danger" id="deleteBtn"
                    onclick="return confirm('⚠️ Are you sure you want to delete the agency? All associated data will be deleted and this action cannot be undone.')">
                    🗑 Delete Agency
                </a>

            </div>
        </form>
    </div>

    <script>

        // Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        function updateImagePreview() {
            const url = imageInput.value;
            if (url && isValidUrl(url)) {
                previewImg.src = url;
                imagePreview.style.display = 'block';
                previewImg.onload = () => {
                    imagePreview.style.animation = 'scaleIn 0.3s ease-out';
                };
                previewImg.onerror = () => {
                    imagePreview.style.display = 'none';
                };
            } else {
                imagePreview.style.display = 'none';
            }
        }

        function isValidUrl(string) {
            try {
                new URL(string);
                return true;
            } catch (_) {
                return false;
            }
        }

        imageInput.addEventListener('input', updateImagePreview);
        updateImagePreview(); // Initial preview

        // Alert system
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alert.style.display = 'block';

            alertContainer.appendChild(alert);

            setTimeout(() => {
                alert.style.animation = 'slideOutTop 0.5s ease-out';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }, 3000);
        }

        // Input validation feedback
        const inputs = document.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function () {
                if (this.checkValidity()) {
                    this.style.borderColor = 'var(--success-color)';
                } else {
                    this.style.borderColor = 'var(--danger-color)';
                }
            });

            input.addEventListener('focus', function () {
                this.style.borderColor = 'var(--primary-color)';
            });
        });

        // Form auto-save indication (optional)
        let saveTimeout;
        inputs.forEach(input => {
            input.addEventListener('input', function () {
                clearTimeout(saveTimeout);
                this.style.borderColor = 'var(--warning-color)';

                saveTimeout = setTimeout(() => {
                    if (this.checkValidity()) {
                        this.style.borderColor = 'var(--success-color)';
                    }
                }, 1000);
            });
        });

        // Smooth scroll to error fields
        function scrollToError(field) {
            field.scrollIntoView({ behavior: 'smooth', block: 'center' });
            field.focus();
        }

        // Add CSS animation for slideOutTop
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOutTop {
                from {
                    opacity: 1;
                    transform: translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateY(-20px);
                }
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        `;
        document.head.appendChild(style);
    </script>



</body>

</html>