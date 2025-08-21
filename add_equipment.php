<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Equipment</title>
    <style>
        body {
            background: #f3f5f9;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            background: #fff;
            margin: auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form label {
            display: block;
            margin: 12px 0 5px;
            font-weight: 600;
        }
        form input, form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .btn-submit {
            background: #007bff;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background: #0056b3;
        }
        #message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>➕ Add Equipment</h2>
    <form id="equipmentForm" action="add_equipment.php" method="POST" onsubmit="return validateForm()">
        <label>Equipment Name</label>
        <input type="text" name="equipment_name" id="equipment_name" required>

        <label>Category</label>
        <input type="text" name="category" id="category" required>

        <label>Purchase Date</label>
        <input type="date" name="purchase_date" id="purchase_date" required>

        <label>Warranty Expiry</label>
        <input type="date" name="warranty_expiry" id="warranty_expiry">

        <label>Quantity</label>
        <input type="number" name="quantity" id="quantity" min="1" required>

        <label>Supplier</label>
        <input type="text" name="supplier" id="supplier" required>

        <label>Condition</label>
        <input type="text" name="condition" id="condition" required>

        <label>Last Maintenance Date</label>
        <input type="date" name="last_maintenance_date" id="last_maintenance_date">

        <button type="submit" class="btn-submit">Add Equipment</button>

    </form>
    <a class="back-link" href="equipment.php">← Back to Equipment List</a>

    <div id="message">
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $stmt = $conn->prepare("INSERT INTO equipment (equipment_name, category, purchase_date, warranty_expiry, quantity, supplier, `condition`, last_maintenance_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisss", $_POST['equipment_name'], $_POST['category'], $_POST['purchase_date'], $_POST['warranty_expiry'], $_POST['quantity'], $_POST['supplier'], $_POST['condition'], $_POST['last_maintenance_date']);
        if ($stmt->execute()) {
            echo "<span style='color:green;'>✅ Equipment added successfully!</span>";
            echo "<script>document.getElementById('equipmentForm').reset();</script>";
        } else {
            echo "<span style='color:red;'>❌ Failed to add equipment.</span>";
        }
    }
    ?>
    </div>
</div>

<script>
    function validateForm() {
        const name = document.getElementById('equipment_name').value.trim();
        const category = document.getElementById('category').value.trim();
        const purchaseDate = document.getElementById('purchase_date').value;
        const quantity = document.getElementById('quantity').value;
        const supplier = document.getElementById('supplier').value.trim();
        const condition = document.getElementById('condition').value.trim();

        if (!name || !category || !purchaseDate || !quantity || !supplier || !condition) {
            alert("Please fill in all required fields.");
            return false;
        }

        if (quantity <= 0) {
            alert("Quantity must be a positive number.");
            return false;
        }

        // Optional: Confirm before submitting
        return confirm("Are you sure you want to add this equipment?");
    }
</script>
</body>
</html>
