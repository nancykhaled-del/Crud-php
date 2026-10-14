<?php
include "../conn.php";
if (isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: post.php?msg=deleted");
    exit;
}
?>