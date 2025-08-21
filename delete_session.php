<?php
include 'db_connect.php';

$session_id = $_GET['id'] ?? null;
if (!$session_id) {
    header('Location: session.php');
    exit;
}

// Delete the session
$stmt = $conn->prepare("DELETE FROM sessions WHERE session_id = ?");
$stmt->bind_param("i", $session_id);

if ($stmt->execute()) {
    header('Location: session.php?msg=deleted');
    exit;
} else {
    echo "Error deleting session: " . $conn->error;
}
