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
                <h1>Welcome to Our Dental Clinic</h1>
                <p>Please log in to your account</p>
            </div>
            <form class="login-form" action="login.signup.controller.php" method="POST">
                <div class="form-group">
                    <input type="email" id="email" name="email" required placeholder=" " />
                    <label for="email">Email</label>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" required placeholder=" " />
                    <label for="password">Password</label>
                </div>
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>
                <button type="submit" name="login" class="login-button">Login</button>
                <div class="register-link">
                    Don't have an account? <a href="signup.php">Register here</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
