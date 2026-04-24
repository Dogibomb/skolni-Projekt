const list = document.getElementById("orderList");

fetch("list_orders.php")
  .then(r => r.json())
  .then(data => {
    if (!data || !data.ok) {
      list.innerHTML = "Chyba: " + (data && data.error ? data.error : "unknown");
      return;
    }

    const orders = data.orders || [];
    if (orders.length === 0) {
      list.innerHTML = "Žádné objednávky";
      return;
    }

    list.innerHTML = "";
    for (let i = 0; i < orders.length; i++) {
      list.innerHTML +=
        "<div class='order'>" +
        "Produkt: " + orders[i].product + "<br>" +
        "Cena: " + orders[i].price + " Kč<br>" +
        "Stav: " + orders[i].status + "<br>" +
        "Čas: " + orders[i].created_at +
        "</div>";
    }
  })
  .catch(err => {
    list.innerHTML = "Chyba: " + err;
  });