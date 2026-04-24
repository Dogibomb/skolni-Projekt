<?php
require_once __DIR__ . "/../includes/bootstrap.php";

$chyba = "";

if (isset($_SESSION["is_admin"])) {
    if ($_SESSION["is_admin"] === true) {
        header("Location: ../index/index.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jmeno = "";
    $heslo = "";

    if (isset($_POST["username"])) {
        $jmeno = $_POST["username"];
    }

    if (isset($_POST["password"])) {
        $heslo = $_POST["password"];
    }

    try {
        $stmt = db()->prepare('select id, name from public."Users" where name = :name and password = :password limit 1');
        $stmt->execute([
            ":name" => $jmeno,
            ":password" => $heslo,
        ]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["name"];
            $_SESSION["is_admin"] = ($user["name"] === "admin");

            header("Location: ../index/index.php");
            exit;
        }

        $_SESSION["is_admin"] = false;
        $_SESSION["username"] = $jmeno;
        $chyba = "Špatné jméno nebo heslo.";
    } catch (Throwable $e) {
        $chyba = "Chyba DB: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Přihlášení</title>
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="../navbar.css">
  <link rel="stylesheet" href="../basicsetup.css">
</head>
<body class="landing">

  <nav class="navbar">
    <a class="logo-link" href="../shop/shop.php"><h1 class="logo">CRM Lite</h1></a>
    <?php if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] === true): ?>
  <ul class="nav-links"> 
    <li><a href="#">Zákazníci</a></li>
    <li><a href="../poznamky/notes.php">Poznámky</a></li> 
    <li><a href="#">Komunikace</a></li> 
    <li><a href="../orders/orders.php">Objednávky</a></li>
  </ul>
  <?php endif; ?>
    <a href="/login/login.php" class="login-btn">Přihlasit se</a>
  </nav>

  <section class="login-box">
    <h2>Přihlášení</h2>

    <?php
    if ($chyba !== "") {
        echo "<p style=\"color: red; margin-bottom: 10px;\">" . $chyba . "</p>";
    }
    ?>

    <form method="POST" action="login.php">
      <input type="text" name="username" placeholder="Uživatelské jméno Př: admin" required>
      <input type="password" name="password" placeholder="Heslo Př: admin" required>
      <button type="submit">Přihlásit se</button>
    </form>
  </section>

</body>
</html>

