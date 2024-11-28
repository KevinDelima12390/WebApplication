<?php
include '../php/php_connection.php';
session_start();

$email = $password = $confirm_password = '';
$email_error = $password_error = $confirm_password_error = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the inputs
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if email is empty
    if (empty($email)) {
        $email_error = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    }

    // Check if passwords match
    if (empty($password)) {
        $password_error = "Password is required.";
    } elseif (strlen($password) < 6) {
        $password_error = "Password must be at least 6 characters.";
    }

    if (empty($confirm_password)) {
        $confirm_password_error = "Confirm Password is required.";
    } elseif ($password !== $confirm_password) {
        $confirm_password_error = "Passwords do not match.";
    }

    // If no errors, proceed to insert the data
    if (empty($email_error) && empty($password_error) && empty($confirm_password_error)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $email, $hashed_password);
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['email'] = $email;
                header("Location: ../home/home.php");
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
        $conn->close();
    }
}
?>
