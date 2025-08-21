<?php
include 'db_connect.php';
$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM equipment WHERE equipment_id = $id");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Equipment</title>
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            background: white;
            margin: auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form label {
            display: block;
            margin-top: 10px;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .btn-submit {
            margin-top: 20px;
            width: 100%;
            background: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-submit:hover {
            background: #218838;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>✏️ Edit Equipment</h2>
    <form method="POST">
        <?php foreach ($data as $key => $value): ?>
            <?php if ($key != 'equipment_id'): ?>
                <label><?= ucfirst(str_replace('_', ' ', $key)) ?></label>
                <input type="<?= ($key === 'purchase_date' || $key === 'warranty_expiry' || $key === 'last_maintenance_date') ? 'date' : 'text' ?>"
                       name="<?= $key ?>" value="<?= htmlspecialchars($value) ?>" required>
            <?php endif; ?>
        <?php endforeach; ?>
        <button class="btn-submit" name="update">Update</button>
    </form>

    <?php
    if (isset($_POST['update'])) {
        $fields = '';
        foreach ($_POST as $key => $val) {
            $fields .= "$key='" . mysqli_real_escape_string($conn, $val) . "', ";
        }
        $fields = rtrim($fields, ', ');
        $update = mysqli_query($conn, "UPDATE equipment SET $fields WHERE equipment_id = $id");
        if ($update) {
            echo "<p style='color:green; text-align:center;'>✅ Updated successfully.</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>❌ Update failed.</p>";
        }
    }
    ?>
</div>
</body>
</html>
