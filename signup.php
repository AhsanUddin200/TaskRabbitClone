<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'taskrabbit');
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    // Retrieve and sanitize form data
    $first_name = isset($_POST['first_name']) ? $conn->real_escape_string($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? $conn->real_escape_string($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? $conn->real_escape_string($_POST['phone']) : '';
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : '';
    $zip_code = isset($_POST['zip_code']) ? $conn->real_escape_string($_POST['zip_code']) : '';

    // File upload logic
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
    $fileName = time() . "_" . basename($_FILES['profile_picture']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
        // Insert into database
        $sql = "INSERT INTO taskrabbit_user (first_name, last_name, email, phone, password, zip_code, profile_picture)
                VALUES ('$first_name', '$last_name', '$email', '$phone', '$password', '$zip_code', '$targetFile')";
        
        if ($conn->query($sql)) {
            echo "<p style='text-align:center; color:green;'>Account created successfully! <a href='login.php'>Login here</a></p>";
        } else {
            echo "<p style='text-align:center; color:red;'>Error: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='text-align:center; color:red;'>Failed to upload profile picture.</p>";
    }

    $conn->close();
}
?>
    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9;
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
        }
        .container h2 {
            text-align: center;
            color: #1a866b;
            margin-bottom: 20px;
            font-size: 1.8rem;
            font-weight: bold;
        }
        .container input, .container button, .container label {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        .container input[type="file"] {
            padding: 5px;
        }
        .container button {
            background: #1a866b;
            color: white;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        .container button:hover {
            background: #146d59;
        }
        .terms {
            text-align: center;
            font-size: 0.9rem;
            color: #777;
        }
        .terms a {
            color: #1a866b;
            text-decoration: none;
        }
        .login {
            text-align: center;
            margin-top: 15px;
            font-size: 1rem;
        }
        .login a {
            color: #1a866b;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>taskrabbit</h2>
        <form method="POST" action="signup.php" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Name" required>
        
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>

            <label for="profile_picture">Upload Profile Picture</label>
            <input type="file" name="profile_picture" accept="image/*" required>
            <button type="submit">Create Account</button>
        </form>
        <p class="terms">By clicking below and creating an account, I agree to TaskRabbit's <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
        <p class="login">Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
