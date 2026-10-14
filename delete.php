<?php
include "../conn.php";
if (isset($_GET['del_id'])) {
    $id = $_GET['del_id'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: categories.php?msg=deleted");
    exit;
}
?>