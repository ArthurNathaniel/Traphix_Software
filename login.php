<?php
include 'db.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        
        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Start session and redirect to dashboard
            session_start();
            $_SESSION['email'] = $email;
            header("Location: all_net.php");
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "No user found with that email address.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luster Cleaning Solutions - Login</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">

</head>
<body>
    <?php include 'head.php' ?>
    <div class="forms_all">
        <div class="forms title">
            <h1>Luster Cleaning Solutions - Login</h1>
        </div>
        <div class="forms">
        <?php if ($error_message): ?>
                <div class="error_message" id="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
        </div>
        <form action="login.php" method="post">
            <div class="forms">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="forms">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="formjhj">
                <input type="checkbox" id="togglePassword" class="toggle-checkbox">
                <label for="togglePassword" class="toggle-label">Show Password</label>
            </div>

            <div class="forms">
                <button type="submit">Login</button>
            </div>

            <div class="forms">
                <p>Forgot password <a href="forgot_password.php">Click here</a></p>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('change', function() {
            var passwordInput = document.getElementById('password');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });

        // Auto-hide error message after 5 seconds
        window.addEventListener('DOMContentLoaded', (event) => {
            var errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.style.opacity = 0;
                    setTimeout(function() {
                        errorMessage.style.display = 'none';
                    }, 600); // Duration should match the fade-out time
                }, 5000); // Time in milliseconds to show the error message
            }
        });
    </script>
</body>
</html>