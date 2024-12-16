<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            box-sizing: border-box;
            text-align: center;
        }
        h2 {
            color: #1a866b;
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: bold;
        }
        input, button, a {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        button {
            background: #1a866b;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        button:hover {
            background: #146d59;
        }
        a {
            display: block;
            color: #1a866b;
            text-decoration: none;
            margin-top: 10px;
            font-weight: bold;
        }
        .forgot-password {
            text-align: right;
            font-size: 0.9rem;
        }
        .signup {
            margin-top: 15px;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>taskrabbit</h2>
        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <div class="forgot-password">
                <a href="#">Forgot password?</a>
            </div>
            <button type="submit">Log in</button>
        </form>
        <p class="signup">Don't have an account? <a href="signup.php">Create Password</a></p>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $conn = new mysqli('localhost', 'root', '', 'taskrabbit');
        if ($conn->connect_error) die('Connection failed: ' . $conn->connect_error);

        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        $sql = "SELECT * FROM taskrabbit_user WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                header("Location: index.php"); // Redirect to index.php
                exit();
            } else {
                echo "<p style='text-align: center; color: red;'>Incorrect password!</p>";
            }
        } else {
            echo "<p style='text-align: center; color: red;'>No user found with this email!</p>";
        }
        $conn->close();
    }
    ?>
</body>
</html>
