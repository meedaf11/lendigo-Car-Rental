<?php

// get_car_details.php
require_once 'functions.php';

if (isset($_GET['car_id'])) {
    $car_id = (int) $_GET['car_id'];
    $car = getCarById($car_id);
    $bookings = getCarBookings($car_id);
    $reviews = getCarReviews($car_id);

    echo json_encode([
        'car' => $car,
        'bookings' => $bookings,
        'reviews' => $reviews
    ]);
}

?>