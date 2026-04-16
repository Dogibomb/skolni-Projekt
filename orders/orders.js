const list = document.getElementById("orderList");

let orders = localStorage.getItem("orders");

if (!orders) {
  list.innerHTML = "Žádné objednávky";
} else {
  orders = JSON.parse(orders);

  for (let i = 0; i < orders.length; i++) {
    list.innerHTML +=
      "<div class='order'>" +
      "Produkt: " + orders[i].product + "<br>" +
      "Cena: " + orders[i].price + " Kč" +
      "</div>";
  }
}