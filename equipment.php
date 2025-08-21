<?php
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Equipment Management</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            font-size: 13px;
            margin-right: 6px;
        }

        .btn-add {
            background-color: #007bff;
            color: white;
        }

        .btn-home {
            background-color: #28a745;
            color: white;
            margin-right: 10px;
        }

        .top-buttons {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .btn-view {
            background-color: #17a2b8;
            color: white;
        }

        .btn-edit {
            background-color: #28a745;
            color: white;
        }
        .btn-delete {
            background-color:#dc3545;
            color:white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .actions {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🛠️ Equipment Inventory</h2>
        <div class="top-buttons">
            <a href="dashboard_admin.php" class="btn btn-home">🏠 Home</a>
            <a href="add_equipment.php" class="btn btn-add">➕ Add Equipment</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Purchase Date</th>
                    <th>Warranty Expiry</th>
                    <th>Quantity</th>
                    <th>Supplier</th>
                    <th>Condition</th>
                    <th>Last Maintenance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM equipment";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['equipment_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['equipment_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['purchase_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['warranty_expiry']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['supplier']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['condition']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['last_maintenance_date']) . "</td>";
                        echo "<td class='actions'>
                                <a href='view_equipment.php?id=" . $row['equipment_id'] . "' class='btn btn-view'>View</a>
                                <a href='edit_equipment.php?id=" . $row['equipment_id'] . "' class='btn btn-edit'>Edit</a>
                                 <a href='delete_equipment.php?id=" . $row['equipment_id'] . "' class='btn btn-delete'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No equipment records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
