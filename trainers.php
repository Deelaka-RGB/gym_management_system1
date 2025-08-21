<?php
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trainer List</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }
        .container {
            padding: 30px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-edit { background-color: #28a745; }
        .btn-delete { background-color: #dc3545; }
        .btn-add { background-color: #007bff; margin-bottom: 15px; display: inline-block; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        td:last-child {
            white-space: nowrap;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>👨‍🏫 Trainer List</h2>
    <a href="add_new_trainer.php" class="btn btn-add">➕ Add New Trainer</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Specialization</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT * FROM trainers";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['trainer_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['specialization']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td>
                        <a href='edit_trainer.php?id=" . $row['trainer_id'] . "' class='btn btn-edit'>Edit</a>
                        <a href='delete_trainer.php?id=" . $row['trainer_id'] . "' class='btn btn-delete' onclick='return confirm(\"Are you sure you want to delete this trainer?\")'>Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No trainers found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
