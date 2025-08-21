<?php
include 'db_connect.php';

$id = $_GET['id'];

// Step 1: Delete related session bookings first
mysqli_query($conn, "DELETE FROM session_bookings WHERE member_id = $id");

// Step 2: Now delete the member
mysqli_query($conn, "DELETE FROM members WHERE member_id = $id");

header("Location: members.php");
?>
