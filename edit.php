<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ? WHERE id = ?");
    $stmt->execute([$title, $description, $id]);

    header('Location: index.php');
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Task</title>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Edit Task</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $task['id'] ?>">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" value="<?= $task['title'] ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description"><?= $task['description'] ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning">Update Task</button>
        </form>
    </div>
</body>
</html>
