<?php
include 'db_connect.php';

// Initialize variables for sticky form and errors
$session_name = $trainer_id = $session_date = $start_time = $end_time = $max_participants = "";
$errors = [];
$success_msg = "";

// Fetch trainers for dropdown
$trainers_result = mysqli_query($conn, "SELECT trainer_id, full_name FROM trainers WHERE status='active' ORDER BY full_name");

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get & sanitize inputs
    $session_name = trim($_POST['session_name']);
    $trainer_id = $_POST['trainer_id'] ?? null;
    $session_date = $_POST['session_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $max_participants = $_POST['max_participants'];

    // Validation
    if (!$session_name) {
        $errors[] = "Session Name is required.";
    }
    if (!$session_date) {
        $errors[] = "Session Date is required.";
    }
    if (!$start_time) {
        $errors[] = "Start Time is required.";
    }
    if (!$end_time) {
        $errors[] = "End Time is required.";
    }
    if (!$max_participants || !filter_var($max_participants, FILTER_VALIDATE_INT, ["options" => ["min_range"=>1]])) {
        $errors[] = "Max Participants must be a positive integer.";
    }
    // Optional: validate trainer_id exists in trainers or allow null

    // If no errors, insert session
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO sessions (session_name, trainer_id, session_date, start_time, end_time, max_participants) VALUES (?, ?, ?, ?, ?, ?)");
        // Bind trainer_id as integer or null
        if ($trainer_id === '' || $trainer_id === null) {
            $trainer_id = null;
            $stmt->bind_param("sssssi", $session_name, $trainer_id, $session_date, $start_time, $end_time, $max_participants);
        } else {
            $stmt->bind_param("sisssi", $session_name, $trainer_id, $session_date, $start_time, $end_time, $max_participants);
        }

        if ($stmt->execute()) {
            $success_msg = "Session added successfully!";
            // Clear form inputs
            $session_name = $trainer_id = $session_date = $start_time = $end_time = $max_participants = "";
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Add New Session</title>
<style>
    /* Same color palette and style as your previous page */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #71b7e6, #9b59b6);
        margin: 0; 
        padding: 0;
        min-height: 100vh;
        color: #333;
    }

    .container {
        max-width: 600px;
        margin: 80px auto 40px;
        background: #fff;
        padding: 30px 40px;
        border-radius: 16px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        position: relative;
    }

    h1 {
        text-align: center;
        font-weight: 700;
        font-size: 2.8rem;
        color: #5a2d82;
        margin-bottom: 30px;
        letter-spacing: 1px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #5a2d82;
    }

    input[type="text"],
    input[type="date"],
    input[type="time"],
    input[type="number"],
    select {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 10px;
        border: 1.8px solid #ddd;
        font-size: 1rem;
        transition: border-color 0.3s ease;
        box-shadow: inset 0 3px 6px rgb(0 0 0 / 0.05);
    }
    input[type="text"]:focus,
    input[type="date"]:focus,
    input[type="time"]:focus,
    input[type="number"]:focus,
    select:focus {
        outline: none;
        border-color: #7b47b6;
        box-shadow: 0 0 8px rgba(123, 71, 182, 0.5);
    }

    .btn-submit {
        background-color: #27ae60;
        color: white;
        padding: 14px 26px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        width: 100%;
        font-size: 1.1rem;
        box-shadow: 0 6px 18px rgba(39, 174, 96, 0.45);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        user-select: none;
    }
    .btn-submit:hover {
        background-color: #219150;
        box-shadow: 0 8px 25px rgba(33, 145, 80, 0.6);
    }

    .home-btn {
        position: fixed;
        top: 20px;
        left: 20px;
        background: #5a2d82;
        color: white;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 6px 15px rgba(90, 45, 130, 0.5);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        z-index: 9999;
    }
    .home-btn:hover {
        background: #7b47b6;
        box-shadow: 0 8px 20px rgba(123, 71, 182, 0.7);
    }

    .error-msg {
        background: #f8d7da;
        border: 1.5px solid #f5c2c7;
        color: #842029;
        border-radius: 10px;
        padding: 14px 20px;
        margin-bottom: 25px;
        font-weight: 600;
    }

    .success-msg {
        background: #d1e7dd;
        border: 1.5px solid #badbcc;
        color: #0f5132;
        border-radius: 10px;
        padding: 14px 20px;
        margin-bottom: 25px;
        font-weight: 600;
        text-align: center;
    }
</style>
</head>
<body>

<a href="session.php" class="home-btn" title="Back to Sessions List">🏠 Home</a>

<div class="container">
    <h1>Add New Session</h1>

    <?php if (!empty($errors)): ?>
        <div class="error-msg">
            <ul style="margin:0; padding-left: 20px;">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success_msg): ?>
        <div class="success-msg"><?= htmlspecialchars($success_msg) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="session_name">Session Name<span style="color:#c0392b;">*</span></label>
        <input type="text" id="session_name" name="session_name" value="<?= htmlspecialchars($session_name) ?>" required />

        <label for="trainer_id">Trainer</label>
        <select id="trainer_id" name="trainer_id">
            <option value="">-- Select Trainer (Optional) --</option>
            <?php
            if ($trainers_result && mysqli_num_rows($trainers_result) > 0) {
                while ($trainer = mysqli_fetch_assoc($trainers_result)) {
                    $selected = ($trainer['trainer_id'] == $trainer_id) ? "selected" : "";
                    echo '<option value="' . $trainer['trainer_id'] . '" ' . $selected . '>' . htmlspecialchars($trainer['full_name']) . '</option>';
                }
            }
            ?>
        </select>

        <label for="session_date">Session Date<span style="color:#c0392b;">*</span></label>
        <input type="date" id="session_date" name="session_date" value="<?= htmlspecialchars($session_date) ?>" required />

        <label for="start_time">Start Time<span style="color:#c0392b;">*</span></label>
        <input type="time" id="start_time" name="start_time" value="<?= htmlspecialchars($start_time) ?>" required />

        <label for="end_time">End Time<span style="color:#c0392b;">*</span></label>
        <input type="time" id="end_time" name="end_time" value="<?= htmlspecialchars($end_time) ?>" required />

        <label for="max_participants">Max Participants<span style="color:#c0392b;">*</span></label>
        <input type="number" id="max_participants" name="max_participants" min="1" value="<?= htmlspecialchars($max_participants) ?>" required />

        <button type="submit" class="btn-submit">Add Session</button>
    </form>
</div>

</body>
</html>
