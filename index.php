<?php
include "../conn.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
  <!-- Navbar -->
  <?php
  include "nav.php"
  
  ?>

  <!-- Sidebar + Main Layout -->
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <?php include "sidebar.php"; ?>

    