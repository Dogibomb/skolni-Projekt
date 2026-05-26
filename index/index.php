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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRM Lite – Dashboard</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="../navbar.css">
  <link rel="stylesheet" href="../basicsetup.css">
</head>
<body>

  <nav class="navbar">
    <a class="logo-link" href="index.php"><h1 class="logo">CRM Lite</h1></a>
    <ul class="nav-links">
      <li><a href="../customers/customers.php">Zákazníci</a></li>
      <li><a href="../poznamky/notes.php">Poznámky</a></li>
      <li><a href="../orders/orders.php">Objednávky</a></li>
    </ul>
    <div class="navbar-right">
      <a href="/login/logout.php" class="login-btn">Odhlásit se</a>
    </div>
  </nav>

  <section class="dashboard-header">
    <h1>Dashboard</h1>
    <p>Vítej zpět, <?= htmlspecialchars($_SESSION["username"]) ?></p>
  </section>

  <section class="info-square">
    <a href="../customers/customers.php">
      <div class="square">
        <img src="../img/contacts.svg" alt="Evidence zákazníků">
        <h3>Evidence zákazníků</h3>
        <p>Ukládání základních kontaktních údajů firem a klientů.</p>
      </div>
    </a>
    <a href="../poznamky/notes.php">
      <div class="square">
        <img src="../img/note.svg" alt="Poznámky">
        <h3>Poznámky</h3>
        <p>Možnost zapisovat důležité informace ke každému zákazníkovi.</p>
      </div>
    </a>
    <a href="../orders/orders.php">
      <div class="square">
        <img src="../img/chat.svg" alt="Objednávky">
        <h3>Objednávky</h3>
        <p>Přehled všech objednávek zákazníků.</p>
      </div>
    </a>
    <a href="../shop/shop.php">
      <div class="square">
        <h3>Obchod</h3>
        <p>Přejít do e-shopu.</p>
      </div>
    </a>
  </section>

</body>
</html>
