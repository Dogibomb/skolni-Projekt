function buyItem(name, price) {
  fetch("create_order.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ product: name, price: price })
  })
    .then(r => {
      if (r.status === 401) {
        window.location.href = "/login/login.php";
        return null;
      }
      return r.json();
    })
    .then(data => {
      if (!data) return;
      if (data.ok) {
        alert("Objednáno!");
      } else {
        alert("Chyba: " + (data.error ?? "unknown"));
      }
    })
    .catch(err => {
      alert("Chyba: " + err);
    });
}