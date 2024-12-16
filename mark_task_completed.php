<?php
include 'db.php';

if (isset($_GET['id'])) {
    $taskId = intval($_GET['id']);
    $sql = "UPDATE taskrabbit_tasks SET status = 'Completed' WHERE id = $taskId";

    if ($conn->query($sql)) {
        echo "Task marked as completed!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
