<?php
session_start();
include '../php/php_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}

$type = $_GET['type'];
$id = $_GET['id'];

if ($type == 'comment') {
    $sql = "DELETE FROM comments WHERE id = ?";
} elseif ($type == 'donation') {
    $sql = "DELETE FROM donations WHERE id = ?";
} elseif ($type == 'user') {
    $sql = "DELETE FROM users WHERE id = ?";
} else {
    echo "Invalid request";
    exit;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
    header('Location: admin_dashboard.php');
} else {
    echo "Error deleting record.";
}

$stmt->close();
$conn->close();
?>
