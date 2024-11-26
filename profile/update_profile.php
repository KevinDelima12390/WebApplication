<?php
include '../php/php_connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($email) || empty($password) || empty($confirm_password)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Update the user's email and password in the database
    $sql = "UPDATE users SET email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssi", $email, $hashed_password, $user_id);

        if ($stmt->execute()) {
            // Update session email
            $_SESSION['email'] = $email;
            header("Location: profile.php");
            exit;
        } else {
            die("Error updating profile: " . $stmt->error);
        }

        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }

    $conn->close();
}
?>
