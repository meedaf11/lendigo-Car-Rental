<?php
require_once __DIR__ . '/functions.php';

$agency_id = $_SESSION['agency_id'] ?? null;

if (!$agency_id) {
    echo "<h3>Agency ID not set. Please log in.</h3>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Car</title>
    <link rel="stylesheet" href="css/addCars.css">
</head>

<body>
    <a href="index.php?page=cars" class="backBtn"> ← Back to Car List</a>

    <div class="edit-car-container">
        <h2>Add New Car</h2>
        <form action="insert_car.php" method="POST" id="addCarForm">

            <div class="form-row">
                <div class="form-group">
                    <label>Car Name:
                        <input type="text" name="car_name" required placeholder="Enter car name">
                    </label>
                </div>
                <div class="form-group">
                    <label>Brand:
                        <input type="text" name="brand" required placeholder="Enter brand">
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Model:
                        <input type="text" name="model" required placeholder="Enter model">
                    </label>
                </div>
                <div class="form-group">
                    <label>Car Type:
                        <input type="text" name="car_type" required placeholder="e.g., SUV, Sedan, Hatchback">
                    </label>
                </div>
            </div>

            <div class="form-row full-width">
                <div class="form-group">
                    <label>Image URL:
                        <input type="text" name="image_url" required placeholder="Enter image URL">
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Fuel Type:
                        <select name="car_fuel" required>
                            <option value="">Select fuel type</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Gasoline">Gasoline</option>
                            <option value="Hybrid">Hybrid</option>
                            <option value="Electrical">Electrical</option>
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label>Transmission:
                        <select name="isAutomatic">
                            <option value="0" selected>Manual</option>
                            <option value="1">Automatic</option>
                        </select>
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Seats:
                        <input type="number" name="places" min="1" max="20" required placeholder="Number of seats">
                    </label>
                </div>
                <div class="form-group">
                    <label>Price/Day (Mad):
                        <input type="number" name="price_per_day"  required placeholder="500 DH">
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kilometers:
                        <input type="number" name="kilometers" min="0" required placeholder="Current mileage">
                    </label>
                </div>
                <div class="form-group">
                    <label>Availability:
                        <select name="availability_status">
                            <option value="available" selected>Available</option>
                            <option value="booked">Booked</option>
                        </select>
                    </label>
                </div>
            </div>

            <div class="form-row full-width">
                <div class="form-group">
                    <label>Description:
                        <textarea name="description" required
                            placeholder="Enter detailed description of the car..."></textarea>
                    </label>
                </div>
            </div>

            <div class="edit-car-buttons">
                <button type="submit" name="action" value="create">
                    ✨ Add New Car
                </button>
            </div>
        </form>
    </div>

    <script>
        // Form validation and enhancement
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('addCarForm');
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');

            // Real-time validation
            inputs.forEach(input => {
                input.addEventListener('blur', function () {
                    validateField(this);
                });

                input.addEventListener('input', function () {
                    if (this.value.trim() !== '') {
                        validateField(this);
                    }
                });
            });

            function validateField(field) {
                const formGroup = field.closest('.form-group');

                if (field.validity.valid && field.value.trim() !== '') {
                    formGroup.classList.remove('has-error');
                    formGroup.classList.add('has-success');
                } else if (!field.validity.valid) {
                    formGroup.classList.remove('has-success');
                    formGroup.classList.add('has-error');
                } else {
                    formGroup.classList.remove('has-success', 'has-error');
                }
            }

            // Form submission
            form.addEventListener('submit', function (e) {
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = 'Adding Car...';

                // Re-enable after 3 seconds (in case of error)
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = '✨ Add New Car';
                }, 3000);
            });

        
        });
    </script>
</body>

</html>