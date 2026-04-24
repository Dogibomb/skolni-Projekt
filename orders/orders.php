<?php
session_start();

$je_admin = false;

if (isset($_SESSION["is_admin"])) {
    if ($_SESSION["is_admin"] === true) {
        $je_admin = true;
    }
}

if ($je_admin === false) {
    header("Location: ../shop/shop.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <title>Historie objednávek</title>
  <link rel="stylesheet" href="orders.css">
  <link rel="stylesheet" href="../navbar.css">
  <link rel="stylesheet" href="../basicsetup.css">
</head>
<body>

  <nav class="navbar">
    <a class="logo-link" href="../index/index.php"><h1 class="logo">CRM Lite</h1></a>
  <ul class="nav-links"> 
    <li><a href="#">Zákazníci</a></li>
    <li><a href="../poznamky/notes.php">Poznámky</a></li> 
    <li><a href="#">Komunikace</a></li> 
    <li><a href="../orders/orders.php">Objednávky</a></li>
  </ul>
    <a href="/login/logout.php" class="login-btn">Odhlásit se</a>
  </nav>


<h1 id="header">Historie objednávek</h1>




<div id="orderList"></div>

<script src="orders.js"></script>
</body>
</html>

