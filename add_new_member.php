<?php
include 'db_connect.php';

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
        echo "<script>alert('Member added successfully!'); window.location.href='member_view.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Member</title>
    <style>
        body {
            background: #f2f5f9;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 25px;
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 18px;
            font-weight: 600;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 10px 12px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #fdfdfd;
            font-size: 15px;
            transition: border 0.3s ease;
        }

        input:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn {
            margin-top: 30px;
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 700px) {
            .form-container {
                margin: 30px 20px;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>➕ Add New Member</h2>
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
</div>

</body>
</html>
