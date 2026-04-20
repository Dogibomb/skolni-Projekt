<?php
session_start();

$admin_jmeno = "admin";
$admin_heslo = "admin";

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

    if ($jmeno === $admin_jmeno && $heslo === $admin_heslo) {
        $_SESSION["is_admin"] = true;
        $_SESSION["username"] = $jmeno;

        header("Location: ../index/index.php");
        exit;
    } else {
        $_SESSION["is_admin"] = false;
        $_SESSION["username"] = $jmeno;
        $chyba = "Špatné jméno nebo heslo.";
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
    <a class="logo-link" href="../shop/shop.html"><h1 class="logo">CRM Lite</h1></a>
  <ul class="nav-links"> 
    <li><a href="#">Zákazníci</a></li>
    <li><a href="../poznamky/notes.php">Poznámky</a></li> 
    <li><a href="#">Komunikace</a></li> 
    <li><a href="../orders/orders.php">Objednávky</a></li>
  </ul>
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

