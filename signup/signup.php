<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700&family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>

<?php
    include 'signup_handler.php';
?>

<div class="container">
    <div class="left">
        <h1>Join the<br><span class="highlight">Journey!</span></h1>
        <div class="text_box">
            <p class="subtext">Already<br> involved?</p>
            <a href="../login/login.php" class="login-link">Login!</a>
        </div>
    </div>

    <div class="right">
        <div class="signup-box">
            <h2>Sign up</h2>
            <form action="signup.php" method="post">
                <!-- Email Input -->
                <input type="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($email); ?>" required>
                <div class="error"><?php echo $email_error; ?></div>

                <!-- Password Input -->
                <input type="password" name="password" placeholder="Password" required>
                <div class="error"><?php echo $password_error; ?></div>

                <!-- Confirm Password Input -->
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <div class="error"><?php echo $confirm_password_error; ?></div>

                <button type="submit">Sign up</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
