<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "gym_management_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$error = '';

// === HANDLE ADD NEW ADMIN ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $admin_name = trim($_POST['admin_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (!$admin_name || !$username || !$email || !$password || !$confirm_password) {
        $error = "All fields are required to add a new admin.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT admin_id FROM admin WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            // Insert new admin
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO admin (admin_name, username, email, password, created_at) VALUES (?, ?, ?, ?, NOW())");
            $insert->bind_param("ssss", $admin_name, $username, $email, $hashedPassword);
            if ($insert->execute()) {
                $message = "New admin added successfully.";
            } else {
                $error = "Failed to add new admin.";
            }
            $insert->close();
        }
        $stmt->close();
    }
}

// === HANDLE DELETE ADMIN ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $delete_id = intval($_POST['admin_id']);
    if ($delete_id === $_SESSION['admin_id']) {
        $error = "You cannot delete your own admin account.";
    } else {
        $del_stmt = $conn->prepare("DELETE FROM admin WHERE admin_id = ?");
        $del_stmt->bind_param("i", $delete_id);
        if ($del_stmt->execute()) {
            $message = "Admin deleted successfully.";
        } else {
            $error = "Failed to delete admin.";
        }
        $del_stmt->close();
    }
}

// === HANDLE EDIT ADMIN ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $edit_id = intval($_POST['admin_id']);
    $admin_name = trim($_POST['admin_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!$admin_name || !$username || !$email) {
        $error = "Name, username, and email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username/email is used by another admin
        $stmt = $conn->prepare("SELECT admin_id FROM admin WHERE (username = ? OR email = ?) AND admin_id != ? LIMIT 1");
        $stmt->bind_param("ssi", $username, $email, $edit_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Username or email already taken by another admin.";
        } else {
            if ($password) {
                if (strlen($password) < 6) {
                    $error = "Password must be at least 6 characters.";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $update = $conn->prepare("UPDATE admin SET admin_name = ?, username = ?, email = ?, password = ? WHERE admin_id = ?");
                    $update->bind_param("ssssi", $admin_name, $username, $email, $hashedPassword, $edit_id);
                }
            } else {
                // No password change
                $update = $conn->prepare("UPDATE admin SET admin_name = ?, username = ?, email = ? WHERE admin_id = ?");
                $update->bind_param("sssi", $admin_name, $username, $email, $edit_id);
            }
            if (!isset($error)) {
                if ($update->execute()) {
                    $message = "Admin updated successfully.";
                } else {
                    $error = "Failed to update admin.";
                }
                $update->close();
            }
        }
        $stmt->close();
    }
}

// Fetch all admins for display
$admins_result = $conn->query("SELECT admin_id, admin_name, username, email, created_at FROM admin ORDER BY admin_id ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Manage Admins - Gym Manager</title>
<style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; max-width: 900px; margin: auto; }
    h1 { color: #004aad; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background: #004aad; color: white; }
    form { margin-top: 30px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input[type=text], input[type=email], input[type=password] { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
    button { margin-top: 15px; background: #004aad; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #003366; }
    .message { color: green; font-weight: bold; margin-top: 15px; }
    .error { color: red; font-weight: bold; margin-top: 15px; }
    .actions form { display: inline-block; margin: 0; }
</style>
<script>
function fillEditForm(id, name, username, email) {
    document.getElementById('edit_admin_id').value = id;
    document.getElementById('edit_admin_name').value = name;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_password').value = '';
    document.getElementById('edit_confirm_password').value = '';
    document.getElementById('edit_form_container').scrollIntoView({ behavior: 'smooth' });
}
</script>
</head>
<body>
<h1>Manage Admins</h1>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- Admin List Table -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($admin = $admins_result->fetch_assoc()): ?>
            <tr>
                <td><?= $admin['admin_id'] ?></td>
                <td><?= htmlspecialchars($admin['admin_name']) ?></td>
                <td><?= htmlspecialchars($admin['email']) ?></td>
                <td><?= $admin['created_at'] ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Add New Admin Form -->
<form method="post">
    <h2>Add New Admin</h2>
    <input type="hidden" name="action" value="add" />
    <label for="admin_name">Name</label>
    <input type="text" name="admin_name" id="admin_name" required />

    <label for="username">Username</label>
    <input type="text" name="username" id="username" required />

    <label for="email">Email</label>
    <input type="email" name="email" id="email" required />

    <label for="password">Password</label>
    <input type="password" name="password" id="password" required />

    <label for="confirm_password">Confirm Password</label>
    <input type="password" name="confirm_password" id="confirm_password" required />

    <button type="submit">Add Admin</button>
</form>

<!-- Edit Admin Form -->
<form method="post" id="edit_form_container">
    <h2>Edit Admin</h2>
    <input type="hidden" name="action" value="edit" />
    <input type="hidden" name="admin_id" id="edit_admin_id" required />

    <label for="edit_admin_name">Name</label>
    <input type="text" name="admin_name" id="edit_admin_name" required />

    <label for="edit_username">Username</label>
    <input type="text" name="username" id="edit_username" required />

    <label for="edit_email">Email</label>
    <input type="email" name="email" id="edit_email" required />

    <label for="edit_password">New Password (leave blank to keep unchanged)</label>
    <input type="password" name="password" id="edit_password" />

    <label for="edit_confirm_password">Confirm New Password</label>
    <input type="password" name="confirm_password" id="edit_confirm_password" />

    <button type="submit">Update Admin</button>
</form>

</body>
</html>
