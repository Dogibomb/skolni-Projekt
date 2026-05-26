<?php
require_once __DIR__ . "/../includes/bootstrap.php";

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: ../shop/shop.php");
    exit;
}

// tady vytahne vsechny uzivatele krome admina
$stmt = db()->query('select id, name, email from public."Users" where name != \'admin\' order by id asc');
$customers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zákazníci</title>
  <link rel="stylesheet" href="customers.css">
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

  <section class="customers-wrap">
    <h1>Zákazníci</h1>

    <?php if (count($customers) === 0): ?>
      <p class="empty">Žádní zákazníci zatím.</p>
    <?php else: ?>
      <?php foreach ($customers as $c): ?>
        <a href="customer.php?user_id=<?= $c['id'] ?>" class="customer-row">
          <span class="customer-name"><?= htmlspecialchars($c['name']) ?></span>
          <span class="customer-email"><?= htmlspecialchars($c['email'] ?? '—') ?></span>
          <span class="customer-arrow">→</span>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>

</body>
</html>
