<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
    $stmt->execute([$title, $description]);

    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Add Task</title>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Add Task</h1>
        <form method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>
    </div>
</body>
</html>
