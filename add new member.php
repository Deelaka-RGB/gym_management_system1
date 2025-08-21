<?php
// Include database connection
include 'db_connect.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $membership_type = $_POST['membership_type'];
    $status = $_POST['status'];

    $sql = "INSERT INTO members (full_name, gender, dob, email, phone, membership_type, status)
            VALUES ('$full_name', '$gender', '$dob', '$email', '$phone', '$membership_type', '$status')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Member added successfully!'); window.location.href='members.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Member</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        h2 {
            margin-bottom: 20px;
        }
        form {
            max-width: 600px;
        }
        label {
            display: block;
            margin-top: 15px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        .btn {
            margin-top: 20px;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<h2>Add New Member</h2>

<form method="POST" action="">
    <label for="full_name">Full Name</label>
    <input type="text" name="full_name" required>

    <label for="gender">Gender</label>
    <select name="gender" required>
        <option value="">-- Select --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>

    <label for="dob">Date of Birth</label>
    <input type="date" name="dob" required>

    <label for="email">Email</label>
    <input type="email" name="email" required>

    <label for="phone">Phone Number</label>
    <input type="text" name="phone" required>

    <label for="membership_type">Membership Plan</label>
    <select name="membership_type" required>
        <option value="">-- Select --</option>
        <option value="Monthly Plan">Monthly Plan</option>
        <option value="Quarterly Plan">Quarterly Plan</option>
        <option value="Annual Plan">Annual Plan</option>
    </select>

    <label for="status">Status</label>
    <select name="status" required>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
    </select>

    <button class="btn" type="submit">Add Member</button>
</form>

</body>
</html>
