<?php
include "../conn.php";
include "nav.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $category_id = $_POST["category_id"] ?? "";
    $status = $_POST["status"] ?? "draft";

   
    $image = $_FILES["image"]["name"] ?? "";
    $target = "../uploads/" . basename($image);

    if (empty($title) || empty($content) || empty($category_id)) {
        $message = "Please fill the required fields (title, content, category).";
        $message_type = "danger";
    } else {
        if (!empty($image)) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $target);
        }

        try {
            $stmt = $conn->prepare("INSERT INTO posts (title, content, category_id,  status, image, created_at) VALUES ( ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$title, $content, $category_id,  $status, $image]);
            $message = "Post added successfully!";
            $message_type = "success";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
            $message_type = "danger";
        }
    }
}


$categories = $conn->query("SELECT * FROM categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <?php include "sidebar.php"; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="py-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2"><i class="bi bi-plus-circle"></i> Add New Post</h1>
          <a href="post.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Posts
          </a>
        </div>

        <?php if (!empty($message)): ?>
          <div class="alert alert-<?= $message_type; ?>"><?= $message; ?></div>
        <?php endif; ?>

    
        <form action="" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Title *</label>
            <input type="text" name="title" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Content *</label>
            <textarea name="content" rows="5" class="form-control" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Category *</label>
            <select name="category_id" class="form-select" required>
              <option value="">-- Select Category --</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id']; ?>"><?= htmlspecialchars($cat['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

         

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="draft">Draft</option>
              <option value="published">Published</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control">
          </div>

          <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Save Post
          </button>
        </form>

      </div>
    </main>
  </div>
</div>
</body>
</html>
