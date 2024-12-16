<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Filters and List</title>
    <style>
        /* General Styles */
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
    overflow-x: hidden;
    position: relative;
}

/* Background Shapes */
body::before,
body::after {
    content: '';
    position: absolute;
    z-index: -1;
    opacity: 0.8;
}

body::before {
    top: -20%;
    left: -10%;
    width: 50%;
    height: 70%;
    background: linear-gradient(135deg, #1a866b, #2ebf8f);
    border-radius: 50%;
    clip-path: ellipse(50% 40% at 30% 30%);
}

body::after {
    bottom: -10%;
    right: -10%;
    width: 40%;
    height: 50%;
    background: linear-gradient(135deg, #e0f79c, #fdf765);
    border-radius: 50%;
    clip-path: ellipse(50% 40% at 70% 70%);
}
        h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #1a866b;
        }
        
        /* Header Styles */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #1a866b;
            color: white;
            padding: 10px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .user-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid #f4f4f9;
            object-fit: cover;
        }
        
        /* Filter Buttons */
        .filter-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .filter-btn {
            background-color: #1a866b;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 0 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s, transform 0.2s;
        }
        .filter-btn:hover, .filter-btn.active {
            background-color: #146d59;
            transform: scale(1.05);
        }
        
        /* Task Cards */
        .task-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 0 10%;
        }
        .task-card {
            background-color: white;
            border: none;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s, transform 0.2s;
        }
        .task-card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }
        .task-card h4 {
            margin: 0 0 10px;
            color: #1a866b;
            font-size: 1.2rem;
        }
        .task-card p {
            margin: 5px 0;
            color: #555;
        }
        .status {
            font-weight: bold;
            text-transform: uppercase;
        }
        .status.pending {
            color: #e0a800;
        }
        .status.done {
            color: #28a745;
        }
        .yellow-ring {
    position: absolute;
    top: 20%;
    right: 10%;
    width: 100px;
    height: 100px;
    border: 8px solid #f9c440;
    border-radius: 50%;
}

/* Green Dotted Pattern */
.green-dots {
    position: absolute;
    bottom: 20%;
    left: 15%;
    display: grid;
    grid-template-columns: repeat(3, 10px);
    grid-gap: 8px;
}

.green-dots div {
    width: 10px;
    height: 10px;
    background-color: #014d41;
    border-radius: 50%;
}

/* Blue Diagonal Lines */
.blue-lines {
    position: absolute;
    top: 10%;
    left: 5%;
    width: 120px;
    height: 200px;
    background-image: repeating-linear-gradient(
        45deg,
        #6e6bff,
        #6e6bff 2px,
        transparent 2px,
        transparent 10px
    );
}
    </style>
</head>
<body>
    <div class="header">
        <h1>Task Dashboard</h1>
        <?php
        // Fetch logged-in user image (example logic, replace with actual session/user logic)
        $userImage = 'path_to_user_image.jpg'; // Replace with actual image path from session or database
        echo "<img src='" . htmlspecialchars($userImage) . "' alt='User Image' class='user-image'>";
        ?>
    </div>

    <h3 style="color: white;">Available Tasks</h3>

    <!-- Filter Buttons -->
    <div class="filter-container">
        <form method="GET" action="">
            <button type="submit" name="category" value="" class="filter-btn <?php if(!isset($_GET['category']) || $_GET['category'] == '') echo 'active'; ?>">All</button>
            <button type="submit" name="category" value="Cleaning" class="filter-btn <?php if(isset($_GET['category']) && $_GET['category'] == 'Cleaning') echo 'active'; ?>">Cleaning</button>
            <button type="submit" name="category" value="Delivery" class="filter-btn <?php if(isset($_GET['category']) && $_GET['category'] == 'Delivery') echo 'active'; ?>">Delivery</button>
            <button type="submit" name="category" value="Repair" class="filter-btn <?php if(isset($_GET['category']) && $_GET['category'] == 'Repair') echo 'active'; ?>">Repair</button>
        </form>
    </div>

    <!-- Task List -->
    <div class="task-list">
        <?php
        // Database Connection
        $conn = new mysqli('localhost', 'root', '', 'taskrabbit');
        if ($conn->connect_error) die('Connection failed: ' . $conn->connect_error);
        
        // Filter Logic
        $categoryFilter = "";
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $category = $conn->real_escape_string($_GET['category']);
            $categoryFilter = " WHERE category = '$category'";
        }
        
        // Fetch Tasks
        $sql = "SELECT title, description, budget, location, status FROM taskrabbit_tasks" . $categoryFilter;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($task = $result->fetch_assoc()) {
                $statusClass = ($task['status'] == 'Completed') ? 'done' : 'pending';
                echo "<div class='task-card'>";
                echo "<h4>" . htmlspecialchars($task['title']) . "</h4>";
                echo "<p><strong>Budget:</strong> $" . htmlspecialchars($task['budget']) . "</p>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($task['location']) . "</p>";
                echo "<p class='status $statusClass'>" . ucfirst($task['status']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center; color: white;'>No tasks found for this category.</p>";
        }
        $conn->close();
        ?>
    </div>
    <div style="padding: 10px 20px;">
    <a href="index.php" style="text-decoration: none;">
        <button style="
            background-color: #146d59; 
            color: white; 
            border: none; 
            padding: 10px 15px; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 1rem;">
            ← Back
        </button>
    </a>
</div>
</div>
</body>
</html>
