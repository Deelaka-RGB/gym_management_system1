<?php
include 'db_connect.php';

// Optional: handle date search filter (for both trainer and member attendance)
$search_date = isset($_GET['date']) ? $_GET['date'] : '';

function fetchAttendance($conn, $table, $date = '') {
    $sql = "SELECT * FROM $table";
    $params = [];
    if ($date) {
        $sql .= " WHERE date = ?";
        $params[] = $date;
    }
    $sql .= " ORDER BY date DESC, attendance_id DESC";

    $stmt = $conn->prepare($sql);
    if ($date) {
        $stmt->bind_param("s", $date);
    }
    $stmt->execute();
    return $stmt->get_result();
}

$trainerAttendance = fetchAttendance($conn, 'trainer_attendance', $search_date);
$memberAttendance = fetchAttendance($conn, 'member_attendance', $search_date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Attendance Records</title>
    <style>
        /* Reset and basic styles */
        *, *::before, *::after {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            margin: 20px;
            color: #222;
            line-height: 1.6;
        }
        h1, h2 {
            text-align: center;
            color: #222;
            margin-bottom: 0.5em;
            font-weight: 700;
        }
        .container {
            max-width: 1300px;
            margin: auto;
            padding: 0 15px 25px;
        }
        form.search-form {
            max-width: 320px;
            margin: 20px auto 40px auto;
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        form.search-form label {
            font-weight: 600;
            align-self: center;
            color: #333;
        }
        input[type=date] {
            padding: 8px 12px;
            font-size: 16px;
            border: 1.8px solid #ccc;
            border-radius: 6px;
            transition: border-color 0.3s ease;
            min-width: 160px;
        }
        input[type=date]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }
        button {
            padding: 8px 18px;
            font-size: 16px;
            cursor: pointer;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            min-width: 100px;
        }
        button:hover {
            background-color: #218838;
        }
        a.clear-btn {
            display: inline-block;
            margin-left: 10px;
            padding: 8px 18px;
            background: #6c757d;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
            min-width: 80px;
            text-align: center;
            line-height: 1;
        }
        a.clear-btn:hover {
            background-color: #5a6268;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 60px;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
            font-size: 15px;
        }
        th, td {
            padding: 14px 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #007bff;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            user-select: none;
            box-shadow: inset 0 -3px 6px rgba(0,0,0,0.2);
        }
        tbody tr:hover {
            background-color: #f1f5f9;
            cursor: default;
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .no-data {
            text-align: center;
            padding: 30px 0;
            font-size: 18px;
            color: #555;
            font-style: italic;
            background: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.07);
            border-radius: 8px;
            margin-bottom: 60px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Buttons for actions */
        .action-btn {
            padding: 6px 12px;
            margin: 0 2px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            color: white;
            display: inline-block;
            transition: background-color 0.3s ease;
            user-select: none;
        }
        .btn-view {
            background-color: #007bff;
        }
        .btn-view:hover {
            background-color: #0056b3;
        }
        
            
        
        

        /* Responsive adjustments */
        @media (max-width: 768px) {
            th, td {
                padding: 10px 8px;
                font-size: 13px;
            }
            form.search-form {
                max-width: 100%;
            }
            input[type=date], button, a.clear-btn {
                min-width: unset;
                flex: 1 1 auto;
            }
            .action-btn {
                font-size: 12px;
                padding: 5px 8px;
                margin: 0 1px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Attendance Records</h1>

    <!-- Search form -->
    <form method="GET" class="search-form" novalidate>
        <label for="date">Filter by Date:</label>
        <input type="date" name="date" id="date" value="<?= htmlspecialchars($search_date) ?>" />
        <button type="submit">Search</button>
        <a href="attendance.php" class="clear-btn">Clear</a>
    </form>

    <!-- Trainer Attendance -->
    <h2>Trainer Attendance</h2>
    <?php if ($trainerAttendance->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Attendance ID</th>
                <th>Trainer ID</th>
                <th>Date</th>
                <th>Check-in Time</th>
                <th>Check-out Time</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $trainerAttendance->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['attendance_id']) ?></td>
                <td><?= htmlspecialchars($row['trainer_id']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['check_in_time']) ?></td>
                <td><?= htmlspecialchars($row['check_out_time']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['remarks'] ?? '') ?></td>
                <td>
                    <a href="view_attendence.php?id=<?= urlencode($row['attendance_id']) ?>" class="action-btn btn-view" title="View">View</a>
                    
            
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="no-data">No trainer attendance records found.</p>
    <?php endif; ?>

    <!-- Member Attendance -->
    <h2>Member Attendance</h2>
    <?php if ($memberAttendance->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Attendance ID</th>
                <th>Member ID</th>
                <th>Date</th>
                <th>Check-in Time</th>
                <th>Check-out Time</th>
                <th>Status</th>
                <th>Remark</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $memberAttendance->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['attendance_id']) ?></td>
                <td><?= htmlspecialchars($row['member_id']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['check_in_time']) ?></td>
                <td><?= htmlspecialchars($row['check_out_time']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['remarks'] ?? '') ?></td>
                <td>
                    <a href="view_attendence.php?id=<?= urlencode($row['attendance_id']) ?>" class="action-btn btn-view" title="View">View</a><br>
            
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="no-data">No member attendance records found.</p>
    <?php endif; ?>
</div>
</body>
</html>
