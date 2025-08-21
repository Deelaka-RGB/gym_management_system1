<?php
session_start();

// Protect page: check if member logged in
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit;
}

$member_id = $_SESSION['member_id'];

// DB connection
$conn = new mysqli("localhost", "root", "", "gym_management_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1) Get monthly attendance summary (present, absent, late counts) grouped by month
$sqlAttendance = "
    SELECT 
        DATE_FORMAT(date, '%Y-%m') AS month,
        SUM(status = 'present') AS present_count,
        SUM(status = 'absent') AS absent_count,
        SUM(status = 'late') AS late_count
    FROM member_attendance
    WHERE member_id = ?
    GROUP BY month
    ORDER BY month DESC
";
$stmt = $conn->prepare($sqlAttendance);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$resultAttendance = $stmt->get_result();

$attendance_summary = [];
while ($row = $resultAttendance->fetch_assoc()) {
    $attendance_summary[] = $row;
}
$stmt->close();

// 2) Get individual absence records (date and remarks)
$sqlAbsents = "
    SELECT date, remarks
    FROM member_attendance
    WHERE member_id = ? AND status = 'absent'
    ORDER BY date DESC
";
$stmt = $conn->prepare($sqlAbsents);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$resultAbsents = $stmt->get_result();

$absents = [];
while ($row = $resultAbsents->fetch_assoc()) {
    $absents[] = $row;
}
$stmt->close();

// 3) Get session bookings with session details
$sqlSessions = "
    SELECT sb.booking_id, sb.booking_date, sb.status, s.session_name, s.session_date, s.start_time, s.end_time 
    FROM sessions_booking sb
    JOIN sessions s ON sb.session_id = s.session_id
    WHERE sb.member_id = ?
    ORDER BY sb.booking_date DESC
";
$stmt = $conn->prepare($sqlSessions);
$stmt->bind_param("i", $member_id);
$stmt->execute();
$resultSessions = $stmt->get_result();

$sessions = [];
while ($row = $resultSessions->fetch_assoc()) {
    $sessions[] = $row;
}
$stmt->close();

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Member Attendance</title>
<style>
    /* Reset */
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        background: #f5f9ff;
        color: #0d2c54;
        padding: 20px;
    }

    h1 {
        color: #0a3d99;
        margin-bottom: 25px;
    }

    .section {
        background: white;
        border-radius: 12px;
        padding: 20px 30px;
        margin-bottom: 30px;
        box-shadow: 0 8px 24px rgba(10, 61, 153, 0.15);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 12px;
    }

    table thead {
        background: #0a3d99;
        color: white;
    }

    table th, table td {
        padding: 12px 15px;
        border-bottom: 1px solid #d9e4ff;
        text-align: left;
        font-size: 15px;
    }

   
    

    .status-present {
        color: green;
        font-weight: 600;
    }

    .status-absent {
        color: red;
        font-weight: 600;
    }

    .status-late {
        color: orange;
        font-weight: 600;
    }

    /* Scroll for smaller devices */
    .table-wrapper {
        overflow-x: auto;
    }

    /* Responsive */
    @media (max-width: 700px) {
        body {
            padding: 10px;
        }
        .section {
            padding: 15px 20px;
        }
        table th, table td {
            padding: 8px 10px;
            font-size: 14px;
        }
    }
</style>
</head>
<body>

<h1>My Attendance & Sessions</h1>

<div class="section">
    <h2>Monthly Attendance Summary</h2>
    <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Late</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($attendance_summary) === 0): ?>
                <tr><td colspan="4" style="text-align:center;">No attendance records found.</td></tr>
            <?php else: ?>
                <?php foreach ($attendance_summary as $att): ?>
                <tr>
                    <td><?php echo htmlspecialchars($att['month']); ?></td>
                    <td class="status-present"><?php echo htmlspecialchars($att['present_count']); ?></td>
                    <td class="status-absent"><?php echo htmlspecialchars($att['absent_count']); ?></td>
                    <td class="status-late"><?php echo htmlspecialchars($att['late_count']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<div class="section">
    <h2>Marked Absents</h2>
    <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($absents) === 0): ?>
                <tr><td colspan="2" style="text-align:center;">No absents marked.</td></tr>
            <?php else: ?>
                <?php foreach ($absents as $abs): ?>
                <tr>
                    <td><?php echo htmlspecialchars($abs['date']); ?></td>
                    <td><?php echo htmlspecialchars($abs['remarks'] ?? '-'); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<div class="section">
    <h2>My Session Bookings</h2>
    <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Booking Date</th>
                <th>Session Name</th>
                <th>Session Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($sessions) === 0): ?>
                <tr><td colspan="5" style="text-align:center;">No session bookings found.</td></tr>
            <?php else: ?>
                <?php foreach ($sessions as $sess): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sess['booking_date']); ?></td>
                    <td><?php echo htmlspecialchars($sess['session_name']); ?></td>
                    <td><?php echo htmlspecialchars($sess['session_date']); ?></td>
                    <td><?php echo htmlspecialchars(substr($sess['start_time'], 0, 5)) . ' - ' . htmlspecialchars(substr($sess['end_time'], 0, 5)); ?></td>
                    <td>
                        <?php
                        $status = $sess['status'];
                        $class = '';
                        if ($status === 'booked') $class = 'status-present';
                        elseif ($status === 'cancelled') $class = 'status-absent';
                        elseif ($status === 'completed') $class = 'status-late';
                        echo '<span class="'.$class.'">'.htmlspecialchars(ucfirst($status)).'</span>';
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

</body>
</html>
