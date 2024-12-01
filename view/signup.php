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
                <h1>Create Your Account</h1>
                <p>Please fill in your details to sign up</p>
            </div>
            <form class="login-form">
                <div class="form-group">
                    <input type="text" id="fullname" name="name" required placeholder=" " />
                    <label for="fullname">Full Name</label>
                </div>
                <div class="form-group">
                    <input type="contact" id="contact" name="contact" required placeholder=" " />
                    <label for="contact">Contact No.</label>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" required placeholder=" " />
                    <label for="email">Email</label>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" required placeholder=" " />
                    <label for="password">Password</label>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="confirm" required placeholder=" " />
                    <label for="password">Confirm Password</label>
                </div>
                <button type="submit" name="register" class="login-button">Register</button>
                <div class="register-link">
                Already have an account? <a href="login.php">Login here</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
