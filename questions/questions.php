<?php
include '../php/php_connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $forum_comment = trim($_POST['forum_comment']);

    if (!empty($forum_comment)) {
        $timestamp = date("Y-m-d H:i:s");
        $sql = "INSERT INTO comments (user_id, forum_comment, timestamp) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("iss", $user_id, $forum_comment, $timestamp);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Fetch all comments with user emails
$sql = "SELECT comments.forum_comment, comments.timestamp, users.email 
        FROM comments 
        JOIN users ON comments.user_id = users.id 
        ORDER BY comments.timestamp DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Questions Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="icon"></div>

    <a href="../questions/questions.php">
    <div class="circle">
        <span class="icon-text">FFA</span>
    </div>
    </a>
    

    <div class="icon-right">
        <div class="home-icon">
            <a href="../home/home.php">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <a href="../profile/profile.php">
            <img src="../images/avatar.png" alt="Avatar">
        </a>
    </div>

    <div class="container">
        <div class="left">
            
            <div class="comments-section">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="comment">
                            <p class="comment-email"><strong><?php echo htmlspecialchars($row['email']); ?></strong></p>
                            <p class="comment-text"><?php echo htmlspecialchars($row['forum_comment']); ?></p>
                            <p class="comment-timestamp"><?php echo date("F j, Y, g:i a", strtotime($row['timestamp'])); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-comments">No comments yet. Be the first to post!</p>
                <?php endif; ?>
            </div>

            
            <div class="input-section">
                <form method="post" action="">
                    <input type="text" name="forum_comment" placeholder="Type your question here..." required>
                    <button type="submit" class="sendbtn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="right">
            <h1>Have <br> Questions?</h1>
            <p>Post them here!</p>
        </div>
    </div>
</body>
</html>
