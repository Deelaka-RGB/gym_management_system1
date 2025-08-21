<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $equipment_id = intval($_GET['id']); // sanitize input

    // Check if equipment exists
    $checkStmt = $conn->prepare("SELECT equipment_id, equipment_name FROM equipment WHERE equipment_id = ?");
    $checkStmt->bind_param("i", $equipment_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        // Equipment not found
        echo "<h3>Equipment with ID " . htmlspecialchars($equipment_id) . " not found.</h3>";
        echo '<p><a href="equipment.php">Go Back</a></p>';
        exit();
    }

    $equipment = $result->fetch_assoc();
    $checkStmt->close();

    // If confirmation received via POST, delete the record
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $stmt = $conn->prepare("DELETE FROM equipment WHERE equipment_id = ?");
        $stmt->bind_param("i", $equipment_id);

        if ($stmt->execute()) {
            header("Location: equipment.php?msg=deleted");
            exit();
        } else {
            echo "<h3>Error deleting equipment: " . htmlspecialchars($conn->error) . "</h3>";
            echo '<p><a href="equipment.php">Go Back</a></p>';
        }
        $stmt->close();
    }
} else {
    echo "<h3>Invalid request. No equipment ID specified.</h3>";
    echo '<p><a href="equipment.php">Go Back</a></p>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Delete Equipment Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }
        .container {
            background: white;
            padding: 30px;
            max-width: 500px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        button {
            cursor: pointer;
            padding: 10px 18px;
            border-radius: 6px;
            border: none;
            margin: 10px;
            font-weight: bold;
            font-size: 16px;
        }
        .btn-confirm {
            background-color: #dc3545;
            color: white;
        }
        .btn-cancel {
            background-color:rgb(4, 15, 26);
            color: white;
        }
        a.btn-home {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            background-color: #28a745;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: bold;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this equipment? This action cannot be undone.');
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Delete Equipment</h2>
        <p>Are you sure you want to delete the equipment:</p>
        <p><strong><?= htmlspecialchars($equipment['equipment_name']) ?></strong></p>

        <form method="POST" onsubmit="return confirmDelete()">
            <input type="hidden" name="confirm" value="yes" />
            <button type="submit" class="btn-confirm">Yes, Delete</button>
            <a href="equipment.php" class="btn-cancel">Cancel</a>
        </form>
        <a href="dashboard_admin.html" class="btn-home">🏠 Home</a>
    </div>
</body>
</html>
