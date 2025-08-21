<?php
session_start();
include 'db_connect.php';

// Assuming member_id is stored in session

$member_id = $_SESSION['member_id'] ?? null;
if (!$member_id) {
    die("You must be logged in to book a personal training session.");
}
// Fetch all trainers for dropdown
$trainers_sql = "SELECT trainer_id, full_name FROM trainers WHERE status='active' ORDER BY full_name";
$trainers_result = mysqli_query($conn, $trainers_sql);

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainer_id = intval($_POST['trainer_id'] ?? 0);
    $booking_date = $_POST['booking_date'] ?? null;
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;

    // Validate inputs
    if (!$trainer_id || !$booking_date || !$start_time || !$end_time) {
        $message = "Please fill all fields.";
    } else {
        // Check if time slot is free for that trainer on that date
        $check_sql = "SELECT * FROM personal_training_bookings 
                      WHERE trainer_id = ? AND booking_date = ? 
                      AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?)) 
                      AND status = 'booked'";

        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("isssss", $trainer_id, $booking_date, $start_time, $start_time, $end_time, $end_time);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $message = "Selected time slot is already booked. Please choose another.";
        } else {
            // Insert booking
            $insert_sql = "INSERT INTO personal_training_bookings 
                (member_id, trainer_id, booking_date, start_time, end_time, status) 
                VALUES (?, ?, ?, ?, ?, 'booked')";
            $stmt2 = $conn->prepare($insert_sql);
            $stmt2->bind_param("iisss", $member_id, $trainer_id, $booking_date, $start_time, $end_time);
            if ($stmt2->execute()) {
                $message = "Booking successful!";
            } else {
                $message = "Error occurred during booking. Please try again.";
            }
        }
    }
}

// Fetch trainers timetable for selected trainer (if trainer selected)
$selected_trainer_id = $_POST['trainer_id'] ?? null;
$timetable = [];
if ($selected_trainer_id) {
    $sql_tt = "SELECT booking_date, start_time, end_time, status FROM personal_training_bookings 
               WHERE trainer_id = ? AND booking_date >= CURDATE() ORDER BY booking_date, start_time";
    $stmt_tt = $conn->prepare($sql_tt);
    $stmt_tt->bind_param("i", $selected_trainer_id);
    $stmt_tt->execute();
    $res_tt = $stmt_tt->get_result();
    while ($row = $res_tt->fetch_assoc()) {
        $timetable[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Book Personal Training</title>
<style>
    /* Using your session page palette & style */
    body {
        font-family: Arial, sans-serif;
        background: #f4f7fa;
        margin: 0; padding: 0;
        color: #333;
    }
    .container {
        max-width: 800px;
        margin: 40px auto;
        background: white;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    h1 {
        color: #007bff;
        text-align: center;
        margin-bottom: 25px;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 30px;
    }
    label {
        font-weight: bold;
    }
    select, input[type="date"], input[type="time"] {
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 16px;
    }
    button {
        width: 150px;
        padding: 12px;
        border: none;
        background-color: #28a745;
        color: white;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
        align-self: flex-start;
    }
    button:hover {
        background-color: #218838;
    }
    .message {
        font-weight: bold;
        color: red;
        margin-bottom: 15px;
    }
    /* Trainer timetable styling */
    .timetable {
        border-collapse: collapse;
        width: 100%;
        box-shadow: 0 0 0 1px #ddd;
        border-radius: 12px;
        overflow: hidden;
    }
    .timetable th, .timetable td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }
    .timetable thead {
        background-color: #007bff;
        color: white;
    }
    .status-booked {
        color: #dc3545;
        font-weight: bold;
    }
    .status-completed {
        color: #28a745;
        font-weight: bold;
    }
    .status-cancelled {
        color: #6c757d;
        font-weight: bold;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Book Personal Training</h1>
    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="trainer_id">Select Trainer:</label>
        <select name="trainer_id" id="trainer_id" required onchange="this.form.submit()">
            <option value="">-- Choose Trainer --</option>
            <?php 
            // Reset pointer so it can be used again
            mysqli_data_seek($trainers_result, 0);
            while($trainer = mysqli_fetch_assoc($trainers_result)): ?>
                <option value="<?php echo $trainer['trainer_id']; ?>" 
                    <?php echo (isset($_POST['trainer_id']) && $_POST['trainer_id'] == $trainer['trainer_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($trainer['full_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <?php if ($selected_trainer_id): ?>
            <label for="booking_date">Select Date:</label>
            <input type="date" name="booking_date" id="booking_date" required
                min="<?php echo date('Y-m-d'); ?>"
                value="<?php echo htmlspecialchars($_POST['booking_date'] ?? ''); ?>" />

            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" id="start_time" required
                value="<?php echo htmlspecialchars($_POST['start_time'] ?? ''); ?>" />

            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" id="end_time" required
                value="<?php echo htmlspecialchars($_POST['end_time'] ?? ''); ?>" />

            <button type="submit">Book Session</button>
        <?php endif; ?>
    </form>

    <?php if ($selected_trainer_id): ?>
        <h2>Trainer's Upcoming Bookings</h2>
        <?php if (count($timetable) > 0): ?>
            <table class="timetable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($timetable as $entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entry['booking_date']); ?></td>
                        <td><?php echo htmlspecialchars($entry['start_time']); ?></td>
                        <td><?php echo htmlspecialchars($entry['end_time']); ?></td>
                        <td class="status-<?php echo strtolower($entry['status']); ?>">
                            <?php echo ucfirst(htmlspecialchars($entry['status'])); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No upcoming bookings for this trainer.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
