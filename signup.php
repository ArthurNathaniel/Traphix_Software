<?php
include 'db.php';

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password before saving to the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $hashed_password);

        if ($stmt->execute()) {
            // Redirect to login page after successful registration
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luster Cleaning Solutions - Signup</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">

</head>
<body>
    <?php include 'head.php' ?>
    <div class="forms_all">
        <div class="forms title">
            <h1>Luster Cleaning Solutions - Signup</h1>
        </div>
        <form action="signup.php" method="post">
            <div class="forms">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>

            <div class="forms">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="forms">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="forms">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="form">
                <input type="checkbox" id="togglePassword" class="toggle-checkbox">
                <label for="togglePassword" class="toggle-label">Show Password</label>
            </div>

            <?php if ($error_message): ?>
                <div class="error_message" id="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <div class="forms">
                <button type="submit">Signup</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('togglePassword').addEventListener('change', function() {
            var passwordInput = document.getElementById('password');
            var confirmPasswordInput = document.getElementById('confirm_password');
            if (this.checked) {
                passwordInput.type = 'text';
                confirmPasswordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
                confirmPasswordInput.type = 'password';
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