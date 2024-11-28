<?php
session_start();
include '../php/php_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM comments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$comment = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $forum_comment = $_POST['forum_comment'];
    $sql = "UPDATE comments SET forum_comment = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $forum_comment, $id);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
    } else {
        echo "Error updating comment.";
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Comment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Edit Comment</h1>
    <form method="POST">
        <textarea name="forum_comment"><?php echo $comment['forum_comment']; ?></textarea><br>
        <button type="submit">Update Comment</button>
    </form>
</body>
</html>
