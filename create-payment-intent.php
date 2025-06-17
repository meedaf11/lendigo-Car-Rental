<?php
require 'vendor/autoload.php'; // Make sure Stripe is installed via Composer

\Stripe\Stripe::setApiKey('sk_test_51QnVHRG278XaJnqxcrpTRuIUb3wmHhKr5zCrc9WRJhGM2ZtCxJwBFnd9q2CEHNnoMmLO9oLjnf4MZJurSdwoEElA00QgJDBoOH'); // Replace with your actual secret key

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$amount = $input['amount'] ?? 0;

try {
    $intent = \Stripe\PaymentIntent::create([
        'amount' => $amount * 100, // Convert to cents
        'currency' => 'mad',       // Or 'usd' depending on your currency
        'description' => 'Agency Registration Fee',
    ]);

    echo json_encode(['clientSecret' => $intent->client_secret]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
