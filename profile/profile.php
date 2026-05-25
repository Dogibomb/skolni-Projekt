<?php
require_once __DIR__ . "/../includes/bootstrap.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login/login.php");
    exit;
}

// tady vytahne udaje uzivatele z databaze
$stmt = db()->prepare('select name, email, password from public."Users" where id = :id limit 1');
$stmt->execute([":id" => $_SESSION["user_id"]]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil</title>
  <link rel="stylesheet" href="../login/login.css">
  <link rel="stylesheet" href="../navbar.css">
  <link rel="stylesheet" href="../basicsetup.css">
</head>
<body>

  <nav class="navbar">
    <a class="logo-link" href="../shop/shop.php"><h1 class="logo">CRM Lite</h1></a>
    <?php if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] === true): ?>
      <ul class="nav-links">
        <li><a href="#">Zákazníci</a></li>
        <li><a href="../poznamky/notes.php">Poznámky</a></li>
        <li><a href="../orders/orders.php">Objednávky</a></li>
      </ul>
    <?php endif; ?>
    <a href="/login/logout.php" class="login-btn">Odhlásit se</a>
  </nav>

  <section class="login-box">
    <h2>Profil</h2>
    <p><strong>Jméno:</strong> <?= htmlspecialchars($user["name"]) ?></p>
    <p style="margin-top: 10px;"><strong>Email:</strong> <?= htmlspecialchars($user["email"]) ?></p>
    <p style="margin-top: 10px;"><strong>Heslo:</strong> <?= htmlspecialchars($user["password"]) ?></p>
  </section>

</body>
</html>