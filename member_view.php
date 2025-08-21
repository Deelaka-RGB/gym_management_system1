<?php
// Include database connection
include 'db_connect.php';

// Fetch members
$sql = "SELECT * FROM members";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Members</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }
        .active { background: #28a745; }
        .inactive { background: #dc3545; }
    </style>
</head>
<body>
    <h2>All Members</h2>

    <table>
        <thead>
            <tr>
                <th>Member ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Date Of Birth</th>
                <th>E mail</th>
                <th>Phone Number</th>
                <th>Plan</th>
                <th>Status</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>GYM" . str_pad($row['member_id'], 3, '0', STR_PAD_LEFT) . "</td>
                        <td>" . $row['full_name'] . "</td>
                        <td>" . $row['gender'] . "</td>
                        <td>" . $row['dob'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['phone'] . "</td>
                        <td>" . $row['membership_type'] . "</td>
                        <td>" . $row['status'] . "</td>
                        <td><span class='status " . strtolower($row['status']) . "'>" . ucfirst($row['status']) . "</span></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No members found.</td></tr>";
            }

            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</body>
</html>
