<?php
include 'db_connect.php';

// Get and validate GET params
$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!in_array($type, ['trainer', 'member']) || $id <= 0) {
    die('Invalid request.');
}

// Define table and ID column based on type
if ($type === 'trainer') {
    $table = 'trainer_attendance';
    $id_column = 'attendance_id';
} else {
    $table = 'member_attendance';
    $id_column = 'attendance_id';
}

// Prepare and execute query
$sql = "SELECT * FROM $table WHERE $id_column = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Attendance record not found.');
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>View <?= htmlspecialchars(ucfirst($type)) ?> Attendance #<?= htmlspecialchars($id) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            padding: 30px;
            color: #222;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #007bff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 16px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f0f0f0;
            width: 40%;
            font-weight: 600;
        }
        tr:hover {
            background: #f9f9f9;
        }
        a.back-link {
            display: inline-block;
            margin-top: 20px;
            background: #28a745;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        a.back-link:hover {
            background: #218838;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>View <?= htmlspecialchars(ucfirst($type)) ?> Attendance #<?= htmlspecialchars($id) ?></h1>
    <table>
        <tr>
            <th>Attendance ID</th>
            <td><?= htmlspecialchars($row['attendance_id']) ?></td>
        </tr>
        <?php if ($type === 'trainer'): ?>
        <tr>
            <th>Trainer ID</th>
            <td><?= htmlspecialchars($row['trainer_id']) ?></td>
        </tr>
        <?php else: ?>
        <tr>
            <th>Member ID</th>
            <td><?= htmlspecialchars($row['member_id']) ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <th>Date</th>
            <td><?= htmlspecialchars($row['date']) ?></td>
        </tr>
        <tr>
            <th>Check-in Time</th>
            <td><?= htmlspecialchars($row['check_in_time']) ?></td>
        </tr>
        <tr>
            <th>Check-out Time</th>
            <td><?= htmlspecialchars($row['check_out_time']) ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= htmlspecialchars($row['status']) ?></td>
        </tr>
        <tr>
            <th>Remarks</th>
            <td><?= htmlspecialchars($row['remarks'] ?? $row['remark'] ?? '') ?></td>
        </tr>
    </table>
    <a href="attendance.php" class="back-link">← Back to Attendance Records</a>
</div>
</body>
</html>
