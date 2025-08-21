<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .btn {
            padding: 8px 16px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-right: 8px;
            display: inline-block;
        }
        .btn-add {
            background: #007bff;
            color: white;
            float: right;
            margin-bottom: 15px;
        }
        .btn-home {
            background: #28a745; /* green */
            color: white;
            float: right;
            margin-bottom: 15px;
            margin-right: 10px;
        }
        .btn-view {
            background-color: #17a2b8; /* teal */
            color: white;
        }
        .btn-print {
            background-color: #ffc107; /* amber */
            color: black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        tfoot td {
            font-weight: bold;
            background-color: #f1f3f5;
            border-top: 2px solid #007bff;
        }
        .button-container {
            overflow: hidden;
            margin-bottom: 15px;
        }
    </style>
    <script>
        function printPayment(id) {
            window.open('print_payment.php?id=' + id, '_blank');
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>💳 Payment Records</h2>
        <div class="button-container">
            <a href="dashboard_admin.php" class="btn btn-home">🏠 Home</a>
            <a href="add_payment.php" class="btn btn-add">➕ Add Payment</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Member ID</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Transaction Ref</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM payments ORDER BY payment_date DESC";
                $result = mysqli_query($conn, $query);
                $totalAmount = 0;

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $totalAmount += $row['amount'];
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['payment_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['member_id']) . "</td>";
                        echo "<td>Rs. " . number_format($row['amount'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['payment_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['payment_method']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['transaction_reference']) . "</td>";
                        echo "<td class='actions'>
                                <a href='view_payment.php?id=" . $row['payment_id'] . "' class='btn btn-view'>View</a>
                                <button onclick='printPayment(" . $row['payment_id'] . ")' class='btn btn-print'>Print</button>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No payment records found.</td></tr>";
                }
                ?>
            </tbody>
            <?php if ($totalAmount > 0): ?>
            <tfoot>
                <tr>
                    <td colspan="2">Total Amount</td>
                    <td colspan="6">Rs. <?= number_format($totalAmount, 2) ?></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
