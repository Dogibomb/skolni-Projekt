const list = document.getElementById("orderList");

// tady nacte objednavky ze serveru
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

    // tady projde vsechny objednavky a vypise je na stranku
    list.innerHTML = orders.map(o => `
      <div class="order">
        <strong>${o.name}</strong> — ${o.email}<br>
        Produkt: ${o.product}<br>
        Cena: ${o.price} Kč<br>
        Stav: ${o.status}<br>
        Čas: ${o.created_at}
      </div>
    `).join("");
  })
  .catch(err => {
    list.innerHTML = "Chyba: " + err;
  });