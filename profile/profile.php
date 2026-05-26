<?php
require_once __DIR__ . "/../includes/bootstrap.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login/login.php");
    exit;
}

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
  <link rel="stylesheet" href="profile.css">
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
      <a href="/profile/profile.php" class="nav-profile-link">Profil</a>
      <a href="/login/logout.php" class="login-btn">Odhlásit se</a>
    </div>
  </nav>

  <section class="profile-wrap">
    <h1>Profil</h1>
    <div class="profile-box">
      <div class="profile-item">
        <span class="profile-label">Jméno</span>
        <span class="profile-value"><?= htmlspecialchars($user["name"]) ?></span>
      </div>
      <div class="profile-item">
        <span class="profile-label">Email</span>
        <span class="profile-value"><?= htmlspecialchars($user["email"] ?? '—') ?></span>
      </div>
      <div class="profile-item">
        <span class="profile-label">Heslo</span>
        <span class="profile-value">
          <span id="heslo-text">••••••••</span>
          <button onclick="toggleHeslo()" id="toggle-btn">Zobrazit</button>
        </span>
      </div>
    </div>
  </section>

  <script>
    // tady prepne mezi zobrazenim a skrytim hesla
    const heslo = "<?= htmlspecialchars($user['password'], ENT_QUOTES) ?>";
    let zobrazeno = false;

    function toggleHeslo() {
      zobrazeno = !zobrazeno;
      document.getElementById("heslo-text").textContent = zobrazeno ? heslo : "••••••••";
      document.getElementById("toggle-btn").textContent = zobrazeno ? "Skrýt" : "Zobrazit";
    }
  </script>

</body>
</html>