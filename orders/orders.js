const list = document.getElementById("orderList");

fetch("list_orders.php")
  .then(r => r.json())
  .then(data => {
    if (!data || !data.ok) {
      list.innerHTML = "Chyba: " + (data?.error ?? "unknown");
      return;
    }

    const orders = data.orders ?? [];

    if (orders.length === 0) {
      list.innerHTML = "Žádné objednávky";
      return;
    }

    // tady projde vsechny objednavky a vypise je, jmeno je odkaz na zakaznika
    list.innerHTML = orders.map(o => `
      <div class="order">
        <a href="../customers/customer.php?user_id=${o.user_id}" class="customer-link">
          <strong>${o.name}</strong>
        </a> — ${o.email}<br>
        Produkt: ${o.product}<br>
        Cena: ${o.price} Kč<br>
        Čas: ${o.created_at}
      </div>
    `).join("");
  })
  .catch(err => {
    list.innerHTML = "Chyba: " + err;
  });