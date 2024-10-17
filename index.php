<?php
include 'db.php';

// Define how many results you want per page
$results_per_page = 5;

// Find out the number of results in the database
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$search_term = '%' . $search_query . '%';

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE title LIKE ?");
$stmt->execute([$search_term]);
$row = $stmt->fetch();
$total_tasks = $row[0];

// Calculate the number of pages needed
$number_of_pages = ceil($total_tasks / $results_per_page);

// Determine which page number visitor is currently on
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Determine the SQL LIMIT starting number for the results on the current page
$start_from = ($current_page - 1) * $results_per_page;

// Determine sorting
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Validate sort options
$allowed_columns = ['id', 'title', 'description'];
$sort_column = in_array($sort_column, $allowed_columns) ? $sort_column : 'id';
$sort_order = ($sort_order === 'DESC') ? 'DESC' : 'ASC';

// Retrieve the tasks for the current page with sorting
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE title LIKE :title ORDER BY $sort_column $sort_order LIMIT :start_from,:results_per_page");
$stmt->bindValue(':title',$search_term);
$stmt->bindValue(':start_from', $start_from, PDO::PARAM_INT);
$stmt->bindValue(':results_per_page', $results_per_page, PDO::PARAM_INT);
$stmt->execute();
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Task Manager</title>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Task Manager</h1>
        <a href="create.php" class="btn btn-primary mb-3">Add Task</a>
		<!-- Search Form -->
        <form class="form-inline mb-3" method="GET" action="index.php">
            <input type="text" name="search" class="form-control mr-2" placeholder="Search by title" value="<?= htmlspecialchars($search_query) ?>">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </form>
		
        <table class="table">
            <thead>
                <tr>
                    <th><a href="?sort=id&order=<?= ($sort_column == 'id' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">ID</a></th>
                    <th><a href="?sort=title&order=<?= ($sort_column == 'title' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">Title</a></th>
                    <th><a href="?sort=description&order=<?= ($sort_column == 'description' && $sort_order == 'ASC') ? 'DESC' : 'ASC' ?>">Description</a></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= $task['id'] ?></td>
                        <td><?= htmlspecialchars($task['title']) ?></td>
                        <td><?= htmlspecialchars($task['description']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $task['id'] ?>" class="btn btn-warning">Edit</a>
                            <a href="delete.php?id=<?= $task['id'] ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php for ($page = 1; $page <= $number_of_pages; $page++): ?>
                    <li class="page-item <?= ($page == $current_page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $page ?>&sort=<?= $sort_column ?>&order=<?= $sort_order ?>"><?= $page ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</body>
</html>
