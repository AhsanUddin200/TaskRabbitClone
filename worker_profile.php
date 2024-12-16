<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Worker Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a866b, #59c8a5);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        h2, h3 {
            text-align: center;
            color: #146d59;
            margin-bottom: 20px;
        }
        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        form input, form textarea, form button {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: 0.3s ease;
        }
        form textarea {
            grid-column: 1 / span 2;
            resize: none;
        }
        form input:focus, form textarea:focus {
            border-color: #1a866b;
            box-shadow: 0 0 5px rgba(26, 134, 107, 0.5);
            outline: none;
        }
        button {
            grid-column: 1 / span 2;
            background-color: #1a866b;
            color: #fff;
            border: none;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s ease;
        }
        button:hover {
            background-color: #146d59;
        }
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #1a866b;
            color: #fff;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #f8f8f8;
        }
        table tr:hover {
            background-color: #e2f6f1;
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            form {
                grid-template-columns: 1fr;
            }
            form textarea, button {
                grid-column: 1 / span 1;
            }
            table th, table td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Worker Profile</h2>
        <form method="POST" action="worker_profile.php">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <textarea name="profile_description" placeholder="Profile Description" rows="4" required></textarea>
            <input type="text" name="skills" placeholder="Skills (e.g., Cleaning, Repair)">
            <button type="submit">Create Profile</button>
        </form>

        <?php
        include 'db.php'; // Database Connection

        // Check if user already exists
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $conn->real_escape_string($_POST['email']);
            $check_sql = "SELECT email FROM taskrabbit_workers WHERE email = '$email'";
            $check_result = $conn->query($check_sql);

            if ($check_result->num_rows > 0) {
                echo "<p style='color: red; text-align: center;'>A profile with this email already exists. You can edit your existing profile.</p>";
            } else {
                // Insert Data if email doesn't exist
                $name = $conn->real_escape_string($_POST['name']);
                $profile_description = $conn->real_escape_string($_POST['profile_description']);
                $skills = $conn->real_escape_string($_POST['skills']);

                $sql = "INSERT INTO taskrabbit_workers (name, email, profile_description, skills) VALUES ('$name', '$email', '$profile_description', '$skills')";
                if ($conn->query($sql)) {
                    echo "<p style='color: green; text-align: center;'>Profile created successfully!</p>";
                } else {
                    echo "<p style='color: red; text-align: center;'>Error: " . $conn->error . "</p>";
                }
            }
        }

        // Fetch Data to Display
        $sql = "SELECT name, email, profile_description, skills, created_at FROM taskrabbit_workers";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h3>Worker Profiles</h3>";
            echo "<table>";
            echo "<tr><th>Name</th><th>Email</th><th>Description</th><th>Skills</th><th>Created At</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['profile_description']) . "</td>";
                echo "<td>" . htmlspecialchars($row['skills']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='text-align: center;'>No profiles found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
