<?php
include '../conn.php';

// -------- 1) جلب post_id من الفورم أو من الرابط --------
$post_id = $_POST['post_id'] ?? $_GET['post_id'] ?? null;

// -------- 2) جلب كل البوستات للـ select --------
$posts_stmt = $conn->prepare("SELECT id, title FROM posts");
$posts_stmt->execute();
$posts = $posts_stmt->fetchAll();

$error = "";

// -------- 3) إضافة الكومنت --------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name   = trim($_POST['name']);
    $email  = trim($_POST['email']);
    $comment = trim($_POST['comment']);

    if ($name && $email && $comment && $post_id) {

        $stmt = $conn->prepare("
            INSERT INTO comments (post_id, name, email, comment)
            VALUES (?,?,?,?)
        ");
        $stmt->execute([$post_id, $name, $email, $comment]);
    } else {
        $error = "All fields and post are required!";
    }
}

// -------- 4) جلب الكومنتات --------
$comment_stmt = $conn->prepare("
    SELECT * FROM comments 
    WHERE post_id=? AND status='approved'
    ORDER BY created_at DESC
");
$comment_stmt->execute([$post_id]);
$comments = $comment_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Comment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Add Comment</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">

        <!-- اختيار المنشور -->
        <div class="mb-2">
            <select name="post_id" class="form-control" required>
                <option value="">Select Post</option>
                <?php foreach ($posts as $post): ?>
                    <option value="<?= $post['id']; ?>"><?= htmlspecialchars($post['title']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text" name="name" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="col-md-6 mb-2">
                <input type="email" name="email" class="form-control" placeholder="Your Email" required>
            </div>
        </div>

        <div class="mb-2">
            <textarea name="comment" class="form-control" rows="3" placeholder="Your Comment" required></textarea>
        </div>
<a href="comment.php" class="btn btn-primary" type="submit">
    post Comment
                </a>
    </form>

    <hr>

    <h3>Comments</h3>
    <?php foreach ($comments as $comment): ?>
        <div class="border p-3 mb-2">
            <strong><?= $comment['name']; ?></strong>
            <small class="text-muted"> - <?= $comment['created_at']; ?></small>
            <p class="mb-0"><?= $comment['comment']; ?></p>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>
