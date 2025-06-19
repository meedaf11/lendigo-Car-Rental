<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

\Stripe\Stripe::setApiKey('sk_test_51QnVHRG278XaJnqxcrpTRuIUb3wmHhKr5zCrc9WRJhGM2ZtCxJwBFnd9q2CEHNnoMmLO9oLjnf4MZJurSdwoEElA00QgJDBoOH'); // استبدل بالمفتاح الحقيقي

$agency_id = $_SESSION['agency_id'] ?? null;
$amount = $_POST['amount'] ?? null;

if (!$agency_id || !$amount) {
    die("بيانات غير صالحة.");
}

// حفظ المبلغ مؤقتًا في جلسة حتى نستعمله بعد الدفع
$_SESSION['pending_payment'] = [
    'agency_id' => $agency_id,
    'amount' => $amount
];

// إنشاء جلسة Checkout
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'mad',
            'product_data' => [
                'name' => 'Add Agency Balance',
            ],
            'unit_amount' => $amount * 100, // Stripe يعمل بـ السنتيم
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://localhost/lendigo-Car-Rental/api/dashboard/AgencyDash/payment_success.php',
    'cancel_url' => 'http://localhost/lendigo-Car-Rental/api/dashboard/AgencyDash/balance.php',
]);

header("Location: " . $session->url);
exit;

