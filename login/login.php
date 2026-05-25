<?php
require_once __DIR__ . "/../includes/bootstrap.php";

$chyba = "";

// pokud je uz prihlasen jako admin, posle ho na hlavni stranku
if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] === true) {
    header("Location: ../index/index.php");
    exit;
}

// tady zpracuje formular kdyz ho uzivatel odesle
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $jmeno = $_POST["username"] ?? "";
    $heslo = $_POST["password"] ?? "";

    try {
        // tady se pokusi najit uzivatele v databazi podle jmena a hesla
        $stmt = db()->prepare('select id, name from public."Users" where name = :name and password = :password limit 1');
        $stmt->execute([
            ":name" => $jmeno,
            ":password" => $heslo,
        ]);
        $user = $stmt->fetch();

        if ($user) {
            // tady ulozi informace o uzivateli do session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["name"];
            $_SESSION["is_admin"] = ($user["name"] === "admin");
            header("Location: ../index/index.php");
            exit;
        }

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
<body>

  <nav class="navbar">
    <a class="logo-link" href="../shop/shop.php"><h1 class="logo">CRM Lite</h1></a>
    <a href="/login/login.php" class="login-btn">Přihlásit se</a>
  </nav>

  <section class="login-box">
    <h2>Přihlášení</h2>

    <?php if ($chyba !== ""): ?>
      <p style="color: red; margin-bottom: 10px;"><?= $chyba ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <input type="text" name="username" placeholder="Uživatelské jméno" required>
      <input type="password" name="password" placeholder="Heslo" required>
      <button type="submit">Přihlásit se</button>
    </form>

    <p class="login-note">Nemáš účet? <a href="../register/register.php">Registrovat se</a></p>
  </section>

</body>
</html>