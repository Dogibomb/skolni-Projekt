<?php
require_once __DIR__ . "/../includes/bootstrap.php";

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: ../shop/shop.php");
    exit;
}

// search parametr
$search = trim($_GET["search"] ?? "");

// zakaznici s objednavkou
if ($search !== "") {
    $stmtWith = db()->prepare('
        SELECT DISTINCT u.id, u.name, u.email
        FROM public."Users" u
        INNER JOIN public.orders o ON o.user_id = u.id
        WHERE u.name != \'admin\'
          AND (LOWER(u.name) LIKE LOWER(:s) OR LOWER(u.email) LIKE LOWER(:s))
        ORDER BY u.name
    ');
    $stmtWith->execute([":s" => "%" . $search . "%"]);
} else {
    $stmtWith = db()->query('
        SELECT DISTINCT u.id, u.name, u.email
        FROM public."Users" u
        INNER JOIN public.orders o ON o.user_id = u.id
        WHERE u.name != \'admin\'
        ORDER BY u.name
    ');
}
$customersWithOrders = $stmtWith->fetchAll();

// zakaznici bez objednavky
if ($search !== "") {
    $stmtNo = db()->prepare('
        SELECT u.id, u.name, u.email
        FROM public."Users" u
        LEFT JOIN public.orders o ON o.user_id = u.id
        WHERE o.user_id IS NULL AND u.name != \'admin\'
          AND (LOWER(u.name) LIKE LOWER(:s) OR LOWER(u.email) LIKE LOWER(:s))
        ORDER BY u.name
    ');
    $stmtNo->execute([":s" => "%" . $search . "%"]);
} else {
    $stmtNo = db()->query('
        SELECT u.id, u.name, u.email
        FROM public."Users" u
        LEFT JOIN public.orders o ON o.user_id = u.id
        WHERE o.user_id IS NULL AND u.name != \'admin\'
        ORDER BY u.name
    ');
}
$customersNoOrders = $stmtNo->fetchAll();
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

    <!-- search bar -->
    <form method="GET" class="search-form">
      <input
        type="text"
        name="search"
        placeholder="Hledat jméno nebo email..."
        value="<?= htmlspecialchars($search) ?>"
        class="search-input"
        autocomplete="off"
      >
      <?php if ($search): ?>
        <a href="customers.php" class="search-clear">✕ Zrušit</a>
      <?php endif; ?>
    </form>

    <!-- tabs -->
    <div class="tabs">
      <button class="tab active" onclick="switchTab('with', this)">
        S objednávkou
        <span class="tab-count"><?= count($customersWithOrders) ?></span>
      </button>
      <button class="tab" onclick="switchTab('no', this)">
        Bez objednávky
        <span class="tab-count"><?= count($customersNoOrders) ?></span>
      </button>
    </div>

    <!-- zakaznici s objednavkou -->
    <div id="tab-with" class="tab-content">
      <?php if (count($customersWithOrders) === 0): ?>
        <p class="empty">Žádní zákazníci<?= $search ? " pro \"" . htmlspecialchars($search) . "\"" : "" ?>.</p>
      <?php else: ?>
        <?php foreach ($customersWithOrders as $c): ?>
          <a href="customer.php?user_id=<?= $c['id'] ?>" class="customer-row">
            <div class="customer-info">
              <div class="customer-name"><?= htmlspecialchars($c['name']) ?></div>
              <div class="customer-email"><?= htmlspecialchars($c['email'] ?? '—') ?></div>
            </div>
            <span class="customer-arrow">→</span>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- zakaznici bez objednavky -->
    <div id="tab-no" class="tab-content" style="display:none">
      <?php if (count($customersNoOrders) === 0): ?>
        <p class="empty">Žádní zákazníci<?= $search ? " pro \"" . htmlspecialchars($search) . "\"" : "" ?>.</p>
      <?php else: ?>
        <?php foreach ($customersNoOrders as $c): ?>
          <a href="customer.php?user_id=<?= $c['id'] ?>" class="customer-row no-order">
            <div class="customer-avatar"><?= htmlspecialchars(mb_substr($c['name'], 0, 1)) ?></div>
            <div class="customer-info">
              <div class="customer-name"><?= htmlspecialchars($c['name']) ?></div>
              <div class="customer-email"><?= htmlspecialchars($c['email'] ?? '—') ?></div>
            </div>
            <span class="customer-arrow">→</span>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <script>
    function switchTab(name, btn) {
      // skryj vsechny taby
      document.querySelectorAll(".tab-content").forEach(t => t.style.display = "none");
      document.querySelectorAll(".tab").forEach(t => t.classList.remove("active"));
      // zobraz spravny tab
      document.getElementById("tab-" + name).style.display = "block";
      btn.classList.add("active");
    }

    // pri vyhledavani odesli formular po krátkém zpozdeni
    const searchInput = document.querySelector(".search-input");
    let searchTimeout;
    searchInput.addEventListener("input", function () {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        searchInput.closest("form").submit();
      }, 400);
    });
  </script>

</body>
</html>
