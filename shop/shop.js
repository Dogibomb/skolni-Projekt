function buyItem(name, price) {
  let orders = localStorage.getItem("orders");

  if (orders === null) {
    orders = [];
  } else {
    orders = JSON.parse(orders);
  }

  orders.push({
    product: name,
    price: price
  });

  localStorage.setItem("orders", JSON.stringify(orders));

  alert("Objednáno");
}
