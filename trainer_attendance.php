<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $attendance_id = $_GET['id'];

    // Prepare query to get attendance record by ID
    $query = "SELECT * FROM trainer_attendance WHERE attendance_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $attendance_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $attendance = $result->fetch_assoc();

    if (!$attendance) {
        echo "Attendance record not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Trainer Attendance</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 12px;
            font-size: 16px;
        }
        .label {
            font-weight: bold;
            color: #333;
            display: inline-block;
            width: 160px;
        }
        .btn-home {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📋 Trainer Attendance Details</h2>
        <div class="info"><span class="label">Attendance ID:</span> <?= htmlspecialchars($attendance['attendance_id']) ?></div>
        <div class="info"><span class="label">Trainer ID:</span> <?= htmlspecialchars($attendance['trainer_id']) ?></div>
        <div class="info"><span class="label">Date:</span> <?= htmlspecialchars($attendance['date']) ?></div>
        <div class="info"><span class="label">Check-in Time:</span> <?= htmlspecialchars($attendance['check_in_time']) ?></div>
        <div class="info"><span class="label">Check-out Time:</span> <?= htmlspecialchars($attendance['check_out_time']) ?></div>
        <div class="info"><span class="label">Status:</span> <?= htmlspecialchars($attendance['status']) ?></div>
        <div class="info"><span class="label">Remarks:</span> <?= htmlspecialchars($attendance['remarks']) ?></div>

        <a href="trainer_attendance_list.php" class="btn-home">🏠 Back to Attendance List</a>
    </div>
</body>
</html>
