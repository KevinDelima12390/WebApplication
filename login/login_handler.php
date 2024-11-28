<?php
include '../php/php_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Invalid email format. Please enter a valid email address.";
        header("Location: login.php");
        exit;
    }

    // 2. Validate password length
    if (strlen($password) < 6) {
        $_SESSION['error_message'] = "Password must be at least 6 characters long.";
        header("Location: login.php");
        exit;
    }

    // 3. SQL query to check user credentials and user role
    $sql = "SELECT id, email, password, user_role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // 4. Verify the password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;

                // Check if the user is an admin
                if ($user['user_role'] === 'admin') {
                    $_SESSION['admin'] = true; // Admin login flag
                    header("Location: ../admin/admin_dashboard.php"); // Redirect to admin dashboard
                    exit;
                } else {
                    header("Location: ../home/home.php"); // Redirect to home page for regular users
                    exit;
                }
            } else {
                $_SESSION['error_message'] = "Invalid password. Please try again.";
                header("Location: login.php");
                exit;
            }
        } else {
            $_SESSION['error_message'] = "No account found with this email address.";
            header("Location: login.php");
            exit;
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Error with the database connection.";
        header("Location: login.php");
        exit;
    }

    $conn->close();
}
?>
