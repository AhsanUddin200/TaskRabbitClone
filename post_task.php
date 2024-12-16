<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Task</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a866b, #59c8a5);
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        .background-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        .background-shapes::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        .background-shapes::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        .container {
            width: 100%;
            max-width: 750px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
            z-index: 1;
        }
        h2 {
            font-size: 1.8rem;
            color: #1a866b;
            margin-bottom: 20px;
            font-weight: bold;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        form input, form textarea, form select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 1rem;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        form input:focus, form textarea:focus, form select:focus {
            border-color: #1a866b;
            box-shadow: 0 0 8px rgba(26, 134, 107, 0.5);
            outline: none;
        }
        form textarea {
            resize: vertical;
            min-height: 100px;
        }
        button {
            padding: 12px;
            background: #1a866b;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        button:hover {
            background: #146d59;
        }
        .success {
            color: #28a745;
            font-size: 1rem;
            margin-top: 20px;
        }
        .error {
            color: #dc3545;
            font-size: 1rem;
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            h2 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="background-shapes"></div>
    <div class="container">
        <h2>Post a Task</h2>
        <form method="POST" action="post_task.php">
            <input type="text" name="title" placeholder="Task Title" required>
            <textarea name="description" placeholder="Task Description" rows="5" required></textarea>
            <input type="number" name="budget" placeholder="Budget (e.g., 500)" required>
            <input type="text" name="location" placeholder="Location" required>
            <select name="category">
                <option value="Cleaning">Cleaning</option>
                <option value="Delivery">Delivery</option>
                <option value="Repair">Repair</option>
            </select>
            <button type="submit">Post Task</button>
        </form>

        <?php
        include 'db.php'; // Include the database connection

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $conn->real_escape_string($_POST['title']);
            $description = $conn->real_escape_string($_POST['description']);
            $budget = $conn->real_escape_string($_POST['budget']);
            $location = $conn->real_escape_string($_POST['location']);
            $category = $conn->real_escape_string($_POST['category']);

            $sql = "INSERT INTO taskrabbit_tasks (title, description, budget, location, category) VALUES ('$title', '$description', '$budget', '$location', '$category')";
            if ($conn->query($sql)) {
                echo "<p class='success'>Task posted successfully!</p>";
            } else {
                echo "<p class='error'>Error: " . $conn->error . "</p>";
            }
        }
        ?>
    </div>
</body>
</html>
