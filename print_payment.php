<?php
include 'db_connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid payment ID.");
}

$payment_id = intval($_GET['id']);

$sql = "SELECT * FROM payments WHERE payment_id = $payment_id LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Payment record not found.");
}

$payment = mysqli_fetch_assoc($result);

$paymentDate = date("F j, Y", strtotime($payment['payment_date']));
$paymentTime = date("h:i A", strtotime($payment['payment_date']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Print Payment #<?= htmlspecialchars($payment['payment_id']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
        }
        h2 {
            text-align: center;
            margin-bottom: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 18px;
        }
        td {
            padding: 12px;
            border: 1px solid #000;
        }
        td.label {
            font-weight: bold;
            background: #f0f0f0;
            width: 40%;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>
    <h2>Payment Receipt - #<?= htmlspecialchars($payment['payment_id']) ?></h2>
    <table>
        <tr>
            <td class="label">Payment ID</td>
            <td><?= htmlspecialchars($payment['payment_id']) ?></td>
        </tr>
        <tr>
            <td class="label">Member ID</td>
            <td><?= htmlspecialchars($payment['member_id']) ?></td>
        </tr>
        <tr>
            <td class="label">Amount</td>
            <td>Rs. <?= number_format($payment['amount'], 2) ?></td>
        </tr>
        <tr>
            <td class="label">Date</td>
            <td><?= $paymentDate ?></td>
        </tr>
        <tr>
            <td class="label">Time</td>
            <td><?= $paymentTime ?></td>
        </tr>
        <tr>
            <td class="label">Payment Method</td>
            <td><?= htmlspecialchars($payment['payment_method']) ?></td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td><?= htmlspecialchars($payment['status']) ?></td>
        </tr>
        <tr>
            <td class="label">Transaction Reference</td>
            <td><?= htmlspecialchars($payment['transaction_reference']) ?></td>
        </tr>
    </table>
</body>
</html>
