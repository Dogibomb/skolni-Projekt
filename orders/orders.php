<?php
session_start();

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: ../shop/shop.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <title>Objednávky</title>
  <link rel="stylesheet" href="orders.css">
  <link rel="stylesheet" href="../navbar.css">
  <link rel="stylesheet" href="../basicsetup.css">
</head>
<body>

  <nav class="navbar">
    <a class="logo-link" href="../index/index.php"><h1 class="logo">CRM Lite</h1></a>
    <ul class="nav-links">
      <li><a href="../customers/customers.php">Zákazníci</a></li>
      <li><a href="../poznamky/notes.php">Poznámky</a></li>
      <li><a href="../orders/orders.php">Objednávky</a></li>
    </ul>
    <div class="navbar-right">
      <a href="/login/logout.php" class="login-btn">Odhlásit se</a>
    </div>
  </nav>

  <section class="orders-wrap">
    <h1>Historie objednávek</h1>
    <div id="orderList"></div>
  </section>

  <script src="orders.js"></script>
</body>
</html>
