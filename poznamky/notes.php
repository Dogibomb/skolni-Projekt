<?php
require_once __DIR__ . "/../includes/bootstrap.php";

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: ../shop/shop.php");
    exit;
}

$search = trim($_GET["search"] ?? "");

// zakaznici s objednavkou (stejne jako customers.php)
if ($search !== "") {
    $stmtCustomers = db()->prepare('
        SELECT DISTINCT u.id, u.name, u.email
        FROM public."Users" u
        INNER JOIN public.orders o ON o.user_id = u.id
        WHERE u.name != \'admin\'
          AND (LOWER(u.name) LIKE LOWER(:s) OR LOWER(u.email) LIKE LOWER(:s))
        ORDER BY u.name
    ');
    $stmtCustomers->execute([":s" => "%" . $search . "%"]);
} else {
    $stmtCustomers = db()->query('
        SELECT DISTINCT u.id, u.name, u.email
        FROM public."Users" u
        INNER JOIN public.orders o ON o.user_id = u.id
        WHERE u.name != \'admin\'
        ORDER BY u.name
    ');
}
$customers = $stmtCustomers->fetchAll();

// vsechny poznamky
$stmtNotes = db()->query('
    SELECT n.id, n.user_id, n.text, n.created_at, u.name as customer_name, u.email as customer_email
    FROM public.notes n
    INNER JOIN public."Users" u ON u.id = n.user_id
    ORDER BY n.created_at DESC
');
$notes = $stmtNotes->fetchAll();
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
      <li><a href="../customers/customers.php">Zákazníci</a></li>
      <li><a href="../poznamky/notes.php">Poznámky</a></li>
      <li><a href="../orders/orders.php">Objednávky</a></li>
    </ul>
    <div class="navbar-right">
      <a href="/login/logout.php" class="login-btn">Odhlásit se</a>
    </div>
  </nav>

  <section class="notes-wrap">
    <h2>Poznámky</h2>

    <!-- search (GET) -->
    <div class="note-box" style="margin-bottom: 16px;">
      <div class="customer-select-wrap">
        <div class="field-group">
          <label for="customerSearch">Vyhledat zákazníka</label>
          <input
            type="text"
            id="customerSearch"
            placeholder="Hledat jméno nebo email…"
            value="<?= htmlspecialchars($search) ?>"
            autocomplete="off"
          >
        </div>
      </div>
    </div>

    <!-- pridat poznamku (POST) -->
    <form method="POST" action="save_note.php" class="note-box" id="addNoteForm">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
      <div class="field-group">
        <label for="customerSelect">Zákazník (pouze ti s objednávkou)</label>
        <select id="customerSelect" name="selected_user" required>
          <option value="">— Vyber zákazníka —</option>
          <?php foreach ($customers as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?><?= $c['email'] ? ' (' . htmlspecialchars($c['email']) . ')' : '' ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <textarea name="text" id="textInput" placeholder="Napiš poznámku…" rows="4"></textarea>
      <button type="submit" id="addBtn">Přidat poznámku</button>
    </form>

    <!-- poznamky -->
    <div id="notesArea">
      <?php if (count($notes) === 0): ?>
        <p class="no-notes">Zatím žádné poznámky.</p>
      <?php else: ?>
        <?php foreach ($notes as $n): ?>
          <div class="note-card">
            <div class="note-card-header">
              <h3><?= htmlspecialchars($n['customer_name']) ?></h3>
              <span class="note-meta"><?= htmlspecialchars($n['created_at']) ?></span>
            </div>
            <?php if ($n['customer_email']): ?>
              <p class="note-email"><?= htmlspecialchars($n['customer_email']) ?></p>
            <?php endif; ?>
            <p class="note-text"><?= htmlspecialchars($n['text']) ?></p>
            <form method="POST" action="save_note.php" style="display:inline">
              <input type="hidden" name="id" value="<?= $n['id'] ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="redirect" value="notes.php?search=<?= urlencode($search) ?>">
              <button type="submit" class="remove-btn">Smazat</button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <script>
    // search s debounce stejne jako customers.php
    const searchInput = document.getElementById("customerSearch");
    let searchTimeout;
    searchInput.addEventListener("input", function () {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        window.location.href = "notes.php?search=" + encodeURIComponent(searchInput.value.trim());
      }, 400);
    });
  </script>
</body>
</html>