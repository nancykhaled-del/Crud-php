<?php
include "../conn.php";
include "nav.php";


$message = "";
$message_type = "";

if (isset($_GET['action']) && isset($_GET['id'])) {
    $comment_id = (int)$_GET['id'];
    $action = $_GET['action'];

    try {
        if ($action === 'approve') {
            $stmt = $conn->prepare("UPDATE comments SET status = 'approved' WHERE id = ?");
            $stmt->execute([$comment_id]);
            $message = "✅ Comment approved successfully!";
            $message_type = "success";
        } elseif ($action === 'reject') {
            $stmt = $conn->prepare("UPDATE comments SET status = 'rejected' WHERE id = ?");
            $stmt->execute([$comment_id]);
            $message = "⚠️ Comment rejected!";
            $message_type = "warning";
        } elseif ($action === 'delete') {
            $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$comment_id]);
            $message = "🗑️ Comment deleted!";
            $message_type = "danger";
        }

        
        echo "<script>window.location.href='comments.php?msg=$message&type=$message_type';</script>";
        exit();
    } catch (PDOException $e) {
        $message = "Database Error: " . $e->getMessage();
        $message_type = "danger";
    }
}


$stmt = $conn->prepare("SELECT * FROM comments ORDER BY created_at DESC");
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count Stats
$total_comments = count($comments);
$pending_comments = $conn->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn();
$approved_comments = $conn->query("SELECT COUNT(*) FROM comments WHERE status = 'approved'")->fetchColumn();
$rejected_comments = $conn->query("SELECT COUNT(*) FROM comments WHERE status = 'rejected'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Comment Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
<div class="container-fluid">
  <div class="row">
   
    <?php include "sidebar.php"; ?>


    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="py-4">

        <?php if (isset($_GET['msg'])): ?>
          <div class="alert alert-<?php echo htmlspecialchars($_GET['type']); ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['msg']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

  
        <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2"><i class="bi bi-chat-dots"></i> Comment Management</h1>
          <a href="Addcomment.php" class="btn btn-primary"><i class="bi bi-plus"></i> Add Comment</a>
        </div>


        <div class="table-responsive">
          <table class="table align-middle table-striped">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Post ID</th>
                <th>Name</th>
                <th>Comment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($comments as $comment): ?>
                <tr>
                  <td><?php echo $comment['id']; ?></td>
                  <td><?php echo $comment['post_id']; ?></td>
                  <td><?php echo htmlspecialchars($comment['name']); ?></td>
                  <td><?php echo htmlspecialchars($comment['comment']); ?></td>
                  <td>
                    <?php if ($comment['status'] === 'approved'): ?>
                      <span class="badge bg-success">Approved</span>
                    <?php elseif ($comment['status'] === 'pending'): ?>
                      <span class="badge bg-warning text-dark">Pending</span>
                    <?php else: ?>
                      <span class="badge bg-danger">Rejected</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></td>
                  <td>
                    <?php if ($comment['status'] !== 'approved'): ?>
                      <a href="comment.php?action=approve&post_id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-success">
                        <i class="bi bi-check"></i> Approve
                      </a>
                    <?php endif; ?>

                    <?php if ($comment['status'] !== 'rejected'): ?>
                      <a href="comment.php?action=reject&post_id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-x"></i> Reject
                      </a>
                    <?php endif; ?>

                    <a href="comment.php?action=delete&post_id=<?php echo $comment['id']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this comment?')">
                      <i class="bi bi-trash"></i> Delete
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>


        <div class="row mt-4">
          <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <h4><?php echo $total_comments; ?></h4>
                  <p class="mb-0">Total Comments</p>
                </div>
                <i class="bi bi-chat-dots fs-1"></i>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <h4><?php echo $pending_comments; ?></h4>
                  <p class="mb-0">Pending</p>
                </div>
                <i class="bi bi-clock fs-1"></i>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <h4><?php echo $approved_comments; ?></h4>
                  <p class="mb-0">Approved</p>
                </div>
                <i class="bi bi-check-circle fs-1"></i>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <h4><?php echo $rejected_comments; ?></h4>
                  <p class="mb-0">Rejected</p>
                </div>
                <i class="bi bi-x-circle fs-1"></i>
              </div>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>
</div>
</body>
</html>
