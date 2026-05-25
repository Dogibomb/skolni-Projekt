<?php
require_once __DIR__ . "/../includes/bootstrap.php";

$chyba = "";
$uspech = "";

// pokud je uz prihlasen, posle ho na index
if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] === true) {
    header("Location: ../index/index.php");
    exit;
}

// tady zpracuje formular kdyz ho uzivatel odesle
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jmeno = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $heslo = $_POST["password"] ?? "";

    if ($jmeno === "" || $email === "" || $heslo === "") {
        $chyba = "Vyplň všechna pole.";
    } else {
        try {
            // tady zkontroluje jestli uz nekdo ma stejne jmeno nebo email
            $check = db()->prepare('select id from public."Users" where name = :name or email = :email limit 1');
            $check->execute([":name" => $jmeno, ":email" => $email]);

            if ($check->fetch()) {
                $chyba = "Uživatel s tímto jménem nebo emailem už existuje.";
            } else {
                // tady vlozi noveho uzivatele do databaze
                $stmt = db()->prepare('insert into public."Users" (name, email, password) values (:name, :email, :password)');
                $stmt->execute([
                    ":name" => $jmeno,
                    ":email" => $email,
                    ":password" => $heslo,
                ]);
                $uspech = "Registrace proběhla úspěšně!";
            }
        } catch (Throwable $e) {
            $chyba = "Chyba DB: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrace</title>
  <link rel="stylesheet" href="../login/login.css">
  <link rel="stylesheet" href="../navbar.css">
  <link rel="stylesheet" href="../basicsetup.css">
</head>
<body>

  <nav class="navbar">
    <a class="logo-link" href="../shop/shop.php"><h1 class="logo">CRM Lite</h1></a>
    <a href="../login/login.php" class="login-btn">Přihlásit se</a>
  </nav>

  <section class="login-box">
    <h2>Registrace</h2>

    <?php if ($uspech !== ""): ?>
      <p style="color: green; margin-bottom: 10px;"><?= $uspech ?></p>
      <a href="../login/login.php">Přihlásit se</a>
    <?php else: ?>

      <?php if ($chyba !== ""): ?>
        <p style="color: red; margin-bottom: 10px;"><?= $chyba ?></p>
      <?php endif; ?>

      <form method="POST" action="register.php">
        <input type="text" name="username" placeholder="Uživatelské jméno" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Heslo" required>
        <button type="submit">Registrovat se</button>
      </form>

      <p class="login-note">Už máš účet? <a href="../login/login.php">Přihlásit se</a></p>

    <?php endif; ?>
  </section>

</body>
</html>