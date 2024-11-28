<?php
// Include the database connection
include '../php/php_connection.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../login/login.php');
    exit;
}

// Fetch user details if user_id is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user details from the database
    $sql = "SELECT id, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        $_SESSION['error_message'] = "User not found.";
        header("Location: admin_dashboard.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "User ID is missing.";
    header("Location: admin_dashboard.php");
    exit;
}

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Initialize error message array
    $error_messages = [];

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_messages[] = "Invalid email format.";
    }

    // Validate password match
    if ($password !== $confirm_password) {
        $error_messages[] = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error_messages[] = "Password must be at least 6 characters.";
    }

    // If there are validation errors, do not proceed with the update
    if (empty($error_messages)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Update the user information in the database
        $update_sql = "UPDATE users SET email = ?, password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssi", $email, $hashed_password, $user_id);

        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = "User details updated successfully.";
            header("Location: admin_dashboard.php"); // Redirect to dashboard after successful update
            exit;
        } else {
            $error_messages[] = "Failed to update user details.";
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="admin-panel">
        <h1>Edit User</h1>

        <!-- Display error or success messages -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <?php if (!empty($error_messages)): ?>
            <div class="alert">
                <?php foreach ($error_messages as $message): ?>
                    <p><?php echo $message; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>

        <!-- Form to edit user -->
        <form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Update User</button>
        </form>

        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>

</body>
</html>
