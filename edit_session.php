<?php
include 'db_connect.php';

$session_id = $_GET['id'] ?? null;
if (!$session_id) {
    header('Location: session.php');
    exit;
}

$error = '';
$success = '';

// Fetch trainers for dropdown
$trainers_result = mysqli_query($conn, "SELECT trainer_id, full_name FROM trainers ORDER BY full_name");

// Fetch existing session data
$stmt = $conn->prepare("SELECT * FROM sessions WHERE session_id = ?");
$stmt->bind_param("i", $session_id);
$stmt->execute();
$session_result = $stmt->get_result();

if ($session_result->num_rows == 0) {
    header('Location: session.php');
    exit;
}

$session = $session_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $session_name = $_POST['session_name'];
    $trainer_id = $_POST['trainer_id'];
    $session_date = $_POST['session_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $max_participants = $_POST['max_participants'];

    // Basic validation
    if (!$session_name || !$session_date || !$start_time || !$end_time || !$max_participants) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("UPDATE sessions SET session_name=?, trainer_id=?, session_date=?, start_time=?, end_time=?, max_participants=? WHERE session_id=?");
        $stmt->bind_param("sisssii", $session_name, $trainer_id, $session_date, $start_time, $end_time, $max_participants, $session_id);
        if ($stmt->execute()) {
            $success = "Session updated successfully.";
            // Refresh session data after update
            $stmt = $conn->prepare("SELECT * FROM sessions WHERE session_id = ?");
            $stmt->bind_param("i", $session_id);
            $stmt->execute();
            $session = $stmt->get_result()->fetch_assoc();
        } else {
            $error = "Error updating session: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Session</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f7fa;
        margin: 0; padding: 0;
    }
    .container {
        max-width: 600px;
        margin: 40px auto;
        background: white;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 25px;
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }
    input[type="text"],
    input[type="date"],
    input[type="time"],
    input[type="number"],
    select {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 18px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }
    input[type="text"]:focus,
    input[type="date"]:focus,
    input[type="time"]:focus,
    input[type="number"]:focus,
    select:focus {
        border-color: #007bff;
        outline: none;
    }
    .btn {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-right: 10px;
        text-decoration: none;
        text-align: center;
    }
    .btn:hover {
        background-color: #0056b3;
    }
    .btn-cancel {
        background-color: #6c757d;
    }
    .btn-cancel:hover {
        background-color: #565e64;
    }
    .message {
        margin-bottom: 15px;
        padding: 10px 15px;
        border-radius: 10px;
        font-weight: bold;
    }
    .error {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
    }
    .success {
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
    }
</style>
</head>
<body>
<div class="container">
    <h1>Edit Session</h1>

    <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="message success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="session_name">Session Name</label>
        <input type="text" id="session_name" name="session_name" value="<?php echo htmlspecialchars($session['session_name']); ?>" required>

        <label for="trainer_id">Trainer</label>
        <select id="trainer_id" name="trainer_id">
            <option value="">-- Select Trainer --</option>
            <?php while ($trainer = mysqli_fetch_assoc($trainers_result)): ?>
                <option value="<?php echo $trainer['trainer_id']; ?>" <?php if ($trainer['trainer_id'] == $session['trainer_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($trainer['full_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="session_date">Date</label>
        <input type="date" id="session_date" name="session_date" value="<?php echo htmlspecialchars($session['session_date']); ?>" required>

        <label for="start_time">Start Time</label>
        <input type="time" id="start_time" name="start_time" value="<?php echo htmlspecialchars($session['start_time']); ?>" required>

        <label for="end_time">End Time</label>
        <input type="time" id="end_time" name="end_time" value="<?php echo htmlspecialchars($session['end_time']); ?>" required>

        <label for="max_participants">Max Participants</label>
        <input type="number" id="max_participants" name="max_participants" min="1" value="<?php echo htmlspecialchars($session['max_participants']); ?>" required>

        <button type="submit" class="btn">Update Session</button>
        <a href="session.php" class="btn btn-cancel">Cancel</a>
    </form>
</div>
</body>
</html>
