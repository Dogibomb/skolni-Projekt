<?php
session_start();

$je_admin = false;

if (isset($_SESSION["is_admin"])) {
    if ($_SESSION["is_admin"] === true) {
        $je_admin = true;
    }
}

if ($je_admin === false) {
    header("Location: ../shop/shop.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Poznámky</title>
  <link rel="stylesheet" href="notes.css">
  <link rel="stylesheet" href="../navbar.css">
  <link rel="stylesheet" href="../basicsetup.css">
</head>
<body>
  <nav class="navbar">
    <a class="logo-link" href="../index/index.php"><h1 class="logo">CRM Lite</h1></a>
  <ul class="nav-links"> 
    <li><a href="#">Zákazníci</a></li>
    <li><a href="../poznamky/notes.php">Poznámky</a></li> 
    <li><a href="#">Komunikace</a></li> 
    <li><a href="../orders/orders.php">Objednávky</a></li>
  </ul>
    <a href="/login/logout.php" class="login-btn">Odhlásit se</a>
  </nav>

    <section class="notes-wrap">
    <h2>Poznámky</h2>

    <form id="addNoteForm" class="note-box">
        <input type="text" id="nameInput" placeholder="Jméno zákazníka">
        <textarea id="textInput" placeholder="Napiš poznámku..."></textarea>
        <button type="submit" id="addBtn">Přidat poznámku</button>
    </form>

    <div id="notesArea"></div>
    </section>
  <script src="note.js"></script>
</body>
</html>

