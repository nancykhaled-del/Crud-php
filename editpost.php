<?php
include "../conn.php";
include "nav.php";

$message = "";
$message_type = "";


if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger m-3'>No posts selected!</div>";
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    echo "<div class='alert alert-danger m-3'>posts not found!</div>";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name)) {
        $message = "Category name is required";
        $message_type = "danger";
    } else {
        $update = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $update->execute([$name, $description, $id]);
        $message = "posts updated successfully!";
        $message_type = "success";

        $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        $category = $stmt->fetch();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Category</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
  <div class="container mt-5">
    <h2><i class="bi bi-pencil-square"></i> Edit posts</h2>

    <!-- Message -->
    <?php if ($message): ?>
      <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">post Name</label>
        <input type="text" class="form-control" name="name" >
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <input type="text" class="form-control" name="description" >
      </div>

      <button type="submit" class="btn btn-success">Update</button>
      <a href="post.php" class="btn btn-secondary">Back</a>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
