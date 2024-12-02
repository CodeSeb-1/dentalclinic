<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-image" role="img" aria-label="Dental clinic interior"></div>
        <div class="login-form-container">
            <img src="../image/image.png" class="logo" alt="logo">
            <div class="login-header">
                <h1>Create a New Password</h1>
                <p>Your new password must not be the same as your previous one.</p>
            </div>
            <form class="login-form" action="forgot.password.controller.php" method="POST">
                <div class="form-group">
                    <input type="email" id="email" name="email" required placeholder=" " />
                    <label for="email">Email</label>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="current_password" required placeholder=" " />
                    <label for="password">Current Password</label>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="new_password" required placeholder=" " />
                    <label for="password">New Password</label>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="confirm_password" required placeholder=" " />
                    <label for="password">Confirm Password</label>
                </div>
                <button type="submit" name="forgotPassword" class="login-button">Confirm</button>
                <div class="register-link">
                    Don't have an account? <a href="signup.php">Register here</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
