<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $equipment_id = $_GET['id'];
    $query = "SELECT * FROM equipment WHERE equipment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $equipment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $equipment = $result->fetch_assoc();

    // Calculate remaining warranty
    $today = new DateTime();
    $expiry = new DateTime($equipment['warranty_expiry']);
    $interval = $today->diff($expiry);
    $remaining_warranty = ($expiry > $today)
        ? $interval->y . " year(s), " . $interval->m . " month(s)"
        : "Expired";
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Equipment</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }
        .container {
            max-width: 700px;
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
        <h2>📋 Equipment Details</h2>
        <div class="info"><span class="label">Name:</span> <?= htmlspecialchars($equipment['equipment_name']) ?></div>
        <div class="info"><span class="label">Category:</span> <?= htmlspecialchars($equipment['category']) ?></div>
        <div class="info"><span class="label">Purchase Date:</span> <?= htmlspecialchars($equipment['purchase_date']) ?></div>
        <div class="info"><span class="label">Warranty Expiry:</span> <?= htmlspecialchars($equipment['warranty_expiry']) ?></div>
        <div class="info"><span class="label">Remaining Warranty:</span> <?= $remaining_warranty ?></div>
        <div class="info"><span class="label">Quantity:</span> <?= htmlspecialchars($equipment['quantity']) ?></div>
        <div class="info"><span class="label">Supplier:</span> <?= htmlspecialchars($equipment['supplier']) ?></div>
        <div class="info"><span class="label">Condition:</span> <?= htmlspecialchars($equipment['condition']) ?></div>
        <div class="info"><span class="label">Last Maintenance Date:</span> <?= htmlspecialchars($equipment['last_maintenance_date']) ?></div>
        <a href="equipment.php" class="btn-home">🏠 Back to Equipment List</a>
    </div>
</body>
</html>
