<?php session_start(); ?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <title>Shop</title>
  <link rel="stylesheet" href="shop.css">
  <link rel="stylesheet" href="../navbar.css">
  <link rel="stylesheet" href="../basicsetup.css">
</head>
<body>

  <nav class="navbar">
    <a class="logo-link" href="../shop/shop.php"><h1 class="logo">CRM Lite</h1></a>

    <?php if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] === true): ?>
      <ul class="nav-links">
        <li><a href="../customers/customers.php">Zákazníci</a></li>
        <li><a href="../poznamky/notes.php">Poznámky</a></li>
        <li><a href="../orders/orders.php">Objednávky</a></li>
      </ul>
    <?php else: ?>
      <ul class="nav-links"></ul>
    <?php endif; ?>

    <div class="navbar-right">
      <?php if (isset($_SESSION["user_id"])): ?>
        <a href="/profile/profile.php" class="nav-profile-link">Profil</a>
        <a href="/login/logout.php" class="login-btn">Odhlásit se</a>
      <?php else: ?>
        <a href="/login/login.php" class="login-btn">Přihlásit se</a>
      <?php endif; ?>
    </div>
  </nav>

  <section class="header">
    <h1>Objednávky služeb</h1>
    <p>Vyber službu a vytvoř objednávku</p>
  </section>

  <section class="shopitems">
    <div class="item">
      <h3>Počítač</h3>
      <p class="item-price">30 000 Kč</p>
      <button onclick="buyItem('Počítač', 30000)">Koupit</button>
    </div>
    <div class="item">
      <h3>Mobil</h3>
      <p class="item-price">10 000 Kč</p>
      <button onclick="buyItem('Mobil', 10000)">Koupit</button>
    </div>
    <div class="item">
      <h3>Tablet</h3>
      <p class="item-price">5 000 Kč</p>
      <button onclick="buyItem('Tablet', 5000)">Koupit</button>
    </div>
  </section>

  <script src="shop.js"></script>
</body>
</html>
