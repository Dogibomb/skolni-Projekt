<?php
require_once __DIR__ . "/../includes/bootstrap.php";

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: ../shop/shop.php");
    exit;
}

$user_id = isset($_GET["user_id"]) ? (int)$_GET["user_id"] : 0;

if ($user_id === 0) {
    header("Location: customers.php");
    exit;
}

// tady vytahne udaje zakaznika z databaze
$stmt = db()->prepare('select id, name, email from public."Users" where id = :id limit 1');
$stmt->execute([":id" => $user_id]);
$customer = $stmt->fetch();

if (!$customer) {
    header("Location: customers.php");
    exit;
}

// tady vytahne vsechny objednavky tohoto zakaznika
$stmt = db()->prepare("select product, price, created_at from public.orders where user_id = :uid order by created_at desc");
$stmt->execute([":uid" => $user_id]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zákazník</title>
  <link rel="stylesheet" href="customer.css">
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

  <section class="customer-wrap">

    <a href="customers.php" class="back-link">← Zpět na zákazníky</a>

    <div class="customer-box">
      <h2><?= htmlspecialchars($customer["name"]) ?></h2>
      <p><strong>Email:</strong> <?= htmlspecialchars($customer["email"] ?? '—') ?></p>
    </div>

    <h3 class="orders-title">Objednávky (<?= count($orders) ?>)</h3>

    <?php if (count($orders) === 0): ?>
      <p class="empty">Žádné objednávky</p>
    <?php else: ?>
      <?php foreach ($orders as $o): ?>
        <div class="order-row">
          <span class="order-product"><?= htmlspecialchars($o["product"]) ?></span>
          <span class="order-price"><?= number_format($o["price"], 0, ',', ' ') ?> Kč</span>
          <span class="order-date"><?= $o["created_at"] ?></span>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </section>

</body>
</html>
