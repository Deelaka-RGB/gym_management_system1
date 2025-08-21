<?php
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gym Members</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f3f6;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s ease; 
            font-weight: bold;
            display: inline-block;
            margin-right: 8px;
        }

        .btn-add {
            background-color: #007bff;
            color: white;
        }

        .btn-add:hover {
            background-color: #0056b3;
        }

        .btn-edit {
            background-color: #28a745;
            color: white;
        }

        .btn-edit:hover {
            background-color: #218838;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-home {
            background-color: #28a745;
            color: white;
        }

        .btn-home:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background-color: #007bff;
            color: white;
        }

        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 10px;
                padding: 10px;
                background: white;
            }

            td {
                padding: 8px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: none;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                color: #555;
            }

            .actions {
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🏋️ Member List</h2>
        <div style="margin-bottom: 20px;">
            <a href="dashboard_admin.php" class="btn btn-home">🏠 Home</a>
            <a href="add_new_member.php" class="btn btn-add">➕ Add New Member</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Membership</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM members";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td data-label='ID'>" . htmlspecialchars($row['member_id']) . "</td>";
                    echo "<td data-label='Full Name'>" . htmlspecialchars($row['full_name']) . "</td>";
                    echo "<td data-label='Gender'>" . htmlspecialchars($row['gender']) . "</td>";
                    echo "<td data-label='DOB'>" . htmlspecialchars($row['dob']) . "</td>";
                    echo "<td data-label='Email'>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td data-label='Phone'>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td data-label='Membership'>" . htmlspecialchars($row['membership_type']) . "</td>";
                    echo "<td data-label='Status'>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td data-label='Actions'>
                            <div class='actions'>
                                <a href='edit_member.php?id=" . $row['member_id'] . "' class='btn btn-edit'>Edit</a>
                                <a href='delete_member.php?id=" . $row['member_id'] . "' class='btn btn-delete' onclick='return confirm(\"Are you sure you want to delete this member?\")'>Delete</a>
                            </div>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No members found</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>
