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

// Format date and time
$paymentDate = date("F j, Y", strtotime($payment['payment_date']));
$paymentTime = date("h:i A", strtotime($payment['payment_date']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Payment #<?= htmlspecialchars($payment['payment_id']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }
        .container {
            max-width: 600px;
            background: white;
            margin: auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        td.label {
            font-weight: bold;
            width: 40%;
            background-color: #f0f0f0;
        }
        a.back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }
        a.back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Payment Details - #<?= htmlspecialchars($payment['payment_id']) ?></h2>
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
    <a href="payment.php" class="back">← Back to Payments</a>
</div>
</body>
</html>
