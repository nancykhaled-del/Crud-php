<?php
include '../conn.php';
include "nav.php";



$message = '';
$message_type = '';

// Handle delete
if (isset($_GET['delete_id'])) {
    $post_id = (int)$_GET['delete_id'];
    
    try {
        // Get image filename to delete from server
        $stmt = $conn->prepare("SELECT image FROM posts WHERE id = ?");
        $stmt->execute([$post_id]);
        $post = $stmt->fetch();
        
        if ($post) {
            // Delete the post
            $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$post_id]);
            
            // Delete image file
            if (!empty($post['image'])) {
                $image_path = '../uploads/' . $post['image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            
            $message = "Post deleted successfully!";
            $message_type = "success";
            
        } else {
            $message = "Post not found!";
            $message_type = "danger";
        }
        
    } catch (PDOException $e) {
        $message = "Error deleting post: " . $e->getMessage();
        $message_type = "danger";
    }
}

// Fetch all posts
try {
    $stmt = $conn->prepare("
        SELECT p.*, c.name as category_name 
        FROM posts p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.id DESC
    ");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Error loading posts: " . $e->getMessage();
    $message_type = "danger";
    $posts = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
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
            <h1 class="h2"><i class="bi bi-file-text"></i> Posts Management</h1> 
             <div class="">
                <a href="addpost.php" class="btn btn-primary">Add New Post</a>
                <a href="display.php" class="btn btn-warning">Veiw Post</a>
            </div>
          </div>
   <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Posts</h5>
        </div>
        <div class="card-body">
            <?php if (empty($posts)): ?>
                <div class="text-center py-4">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h4 class="text-muted">No Posts Found</h4>
                    <p class="text-muted">Get started by creating your first post.</p>
                    <a href="addpost.php" class="btn btn-primary">Create Your First Post</a>
                </div>
            <?php else: ?>
        </div>
    </div>

 
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?php echo $post['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                                        <?php if (!empty($post['image'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-image"></i> Has image
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if (!empty($post['category_name'])) {
                                            echo htmlspecialchars($post['category_name']);
                                        } else {
                                            echo '<span class="text-muted">Uncategorized</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status_class = $post['status'] == 'published' ? 'bg-success' : 'bg-secondary';
                                        $status_text = ucfirst($post['status']);
                                        ?>
                                        <span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        if (isset($post['created_at'])) {
                                            echo date('M j, Y', strtotime($post['created_at']));
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn">
                                            <a href="editpost.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <a href="deletepost.php?del_id=<?php echo $post['id']; ?>" 
                                               class="btn btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this post?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>

                                             <a href="displaypost.php?id=<?php echo $post['id']; ?>" class="btn btn-warning">
                                                <i class="bi bi-eye"></i> Veiw
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

</body>
</html>
