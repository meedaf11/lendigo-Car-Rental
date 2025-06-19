<?php
require_once __DIR__ . '/functions.php';
global $pdo;

if (!isset($_SESSION['pending_payment'])) {
    die("لا توجد معلومات دفع.");
}

$agency_id = $_SESSION['pending_payment']['agency_id'];
$amount = $_SESSION['pending_payment']['amount'];

// تحديث الرصيد
$pdo->prepare("UPDATE agency SET solde = solde + :amount WHERE agency_id = :id")
    ->execute([
        'amount' => $amount,
        'id' => $agency_id
]);

// إضافة سجل الدفع
addPayment($agency_id, $amount, 'stripe', 'completed', $pdo);

// حذف الجلسة
unset($_SESSION['pending_payment']);

// إعادة التوجيه
header("Location: index.php?page=addBalance&status=paymentSuccess");
exit;
