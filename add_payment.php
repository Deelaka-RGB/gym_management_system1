<?php
include 'db_connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and get form data
    $member_id = $_POST['member_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];
    $transaction_reference = $_POST['transaction_reference'];

    // Validate member exists
    $checkMember = $conn->prepare("SELECT member_id FROM members WHERE member_id = ?");
    $checkMember->bind_param("i", $member_id);
    $checkMember->execute();
    $checkMember->store_result();

    if ($checkMember->num_rows === 0) {
        $error = "Selected member does not exist. Please choose a valid member.";
    } else {
        // Insert payment record
        $stmt = $conn->prepare("INSERT INTO payments (member_id, amount, payment_date, payment_method, status, transaction_reference) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idssss", $member_id, $amount, $payment_date, $payment_method, $status, $transaction_reference);

        if ($stmt->execute()) {
            $success = "Payment added successfully!";
            // Clear form values
            $_POST = [];
        } else {
            $error = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
    $checkMember->close();
}

// Fetch members for dropdown
$members_result = $conn->query("SELECT member_id, full_name FROM members ORDER BY full_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Payment</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 25px;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #444;
        }
        select, input[type="text"], input[type="number"], input[type="date"], input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            font-weight: 700;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
        }
        .error {
            background-color: #f8d7da;
            color: #842029;
        }
        .success {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        a.back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }
        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>➕ Add New Payment</h2>

    <?php if ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="message success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="member_id">Select Member *</label>
        <select name="member_id" id="member_id" required>
            <option value="" disabled <?= empty($_POST['member_id']) ? 'selected' : '' ?>>-- Select Member --</option>
            <?php
            if ($members_result->num_rows > 0) {
                while ($member = $members_result->fetch_assoc()) {
                    $selected = (isset($_POST['member_id']) && $_POST['member_id'] == $member['member_id']) ? "selected" : "";
                    echo "<option value='" . htmlspecialchars($member['member_id']) . "' $selected>" . htmlspecialchars($member['full_name']) . " (ID: " . $member['member_id'] . ")</option>";
                }
            } else {
                echo "<option value='' disabled>No members found</option>";
            }
            ?>
        </select>

        <label for="amount">Amount (Rs.) *</label>
        <input type="number" name="amount" id="amount" min="0" step="0.01" required value="<?= isset($_POST['amount']) ? htmlspecialchars($_POST['amount']) : '' ?>">

        <label for="payment_date">Payment Date *</label>
        <input type="date" name="payment_date" id="payment_date" required value="<?= isset($_POST['payment_date']) ? htmlspecialchars($_POST['payment_date']) : date('Y-m-d') ?>">

        <label for="payment_method">Payment Method *</label>
        <select name="payment_method" id="payment_method" required>
            <option value="" disabled <?= empty($_POST['payment_method']) ? 'selected' : '' ?>>-- Select Method --</option>
            <option value="Cash" <?= (isset($_POST['payment_method']) && $_POST['payment_method'] == 'Cash') ? 'selected' : '' ?>>Cash</option>
            <option value="Card" <?= (isset($_POST['payment_method']) && $_POST['payment_method'] == 'Card') ? 'selected' : '' ?>>Card</option>
            <option value="Online" <?= (isset($_POST['payment_method']) && $_POST['payment_method'] == 'Online') ? 'selected' : '' ?>>Online</option>
        </select>

        <label for="status">Status *</label>
        <select name="status" id="status" required>
            <option value="" disabled <?= empty($_POST['status']) ? 'selected' : '' ?>>-- Select Status --</option>
            <option value="Paid" <?= (isset($_POST['status']) && $_POST['status'] == 'Paid') ? 'selected' : '' ?>>Paid</option>
            <option value="Failed" <?= (isset($_POST['status']) && $_POST['status'] == 'Failed') ? 'selected' : '' ?>>Failed</option>
            <option value="Refunded" <?= (isset($_POST['status']) && $_POST['status'] == 'Refunded') ? 'selected' : '' ?>>Refunded</option>
        </select>

        <label for="transaction_reference">Transaction Reference</label>
        <input type="text" name="transaction_reference" id="transaction_reference" value="<?= isset($_POST['transaction_reference']) ? htmlspecialchars($_POST['transaction_reference']) : '' ?>" placeholder="Optional">

        <button type="submit">Add Payment</button>
    </form>

    <a href="payment.php" class="back-link">← Back to Payments List</a>
</div>
</body>
</html>
