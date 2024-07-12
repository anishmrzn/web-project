<?php
session_start();
include 'connection.php'; // Ensure this file sets up the $con variable

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $con->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: home.php"); // Redirect to home page
            exit(); // Ensure no further code is executed after redirection
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }

    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        .password-container {
            display: flex;
            align-items: center;
        }
        .password-container input[type="password"], .password-container input[type="text"] {
            flex: 1;
        }
    </style>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById('password');
            var toggleBtn = document.getElementById('toggleBtn');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleBtn.textContent = 'Hide';
            } else {
                passwordField.type = 'password';
                toggleBtn.textContent = 'Show';
            }
        }
    </script>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="">
        Username: <input type="text" name="username" required><br>
        <div class="password-container">
            Password: <input type="password" name="password" id="password" required>
            <button type="button" id="toggleBtn" onclick="togglePassword()">Show</button>
        </div><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
