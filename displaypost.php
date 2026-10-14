<?php
include "../conn.php";
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetchAll();

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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="#">My Blog</a>
     
    </div>
  </nav>
    <h3 class="mt-3">Latest Posts</h3>
        <?php foreach ($post as $post): ?> 
       
  <div class="d-flex border-bottom pb-3 mb-3"> 
            <?php if (!empty($post['image'])): ?> 
            <div class="flex-shrink-0 me-3"> 
                <img src="../uploads/<?php echo $post['image']; ?>" alt="<?php echo 
$post['title']; ?>" width="150" class="rounded"> 
            </div> 
            <?php endif; ?> 
             
            <div class="flex-grow-1"> 
           
      <h4><?php echo $post['title']; ?></h4> 
               
  <p class="text-muted mb-2"> 
                    <?php echo date('F j, Y', strtotime($post['created_at'])); ?> 
                </p> 
              
   <p class="mb-2"> 
                    <?php  
                    $content = strip_tags($post['content']); 
                    echo substr($content, 0, 200) . '...';  
                    ?> 
                </p> 
 
                <a href="details.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">Read More</a> 
                <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary">Back</a> 
            </div> 
        </div> 
        <?php endforeach; ?> 
    </div>
</body>
</html>