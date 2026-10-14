<?php
include "../conn.php";
include "nav.php";

$message="";
$message_type="";

if($_SERVER['REQUEST_METHOD']=='POST'){
    $name=$_POST['name'];
    $description=$_POST['description'];
    if(empty($name)){
        $message='category name is required';
        $message_type='danger';
    }else{
        $stmt=$conn->prepare('INSERT INTO categories( `name`, `description` ) VALUES (?,?)');
        $stmt->execute([$name,$description]);
        $message="Category added successfully!";
        $message_type="success";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Categories Management</title>
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
            <h1 class="h2"><i class="bi bi-tags"></i> Categories Management</h1> 
          </div>

          <!-- alert message -->
          <?php if ($message): ?>
          <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <?php endif; ?>

          <!-- form -->
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Category Name</label>
              <input type="text" class="form-control" name="name">
            </div>

            <div class="mb-3">
              <label class="form-label">Description</label>
              <input type="text" class="form-control" name="description">
            </div>

            <button type="submit" class="btn btn-primary">Add</button>
          </form>
        </div>

        <h5>All Categories</h5>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">iD</th>
              <th scope="col">Name</th>
              <th scope="col">Description</th>
              <th scope="col">Created at</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt=$conn->query('SELECT * FROM categories');
            while($row=$stmt->fetch()):
            ?>
            <tr>
              <th scope="row"><?=$row['id']?></th>
              <td><?=$row['name']?></td>
              <td><?=$row['description']?></td>
              <td><?=$row['created_at']?></td>
              <td>
               <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
  <i class="bi bi-pencil"></i> Edit
</a>

                <a href="delete.php?del_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                  <i class="bi bi-trash"></i> Delete
                </a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
