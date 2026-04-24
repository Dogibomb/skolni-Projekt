function buyItem(name, price) {
  fetch("create_order.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ product: name, price: price })
  })
    .then(r => r.json())
    .then(data => {
      if (data && data.ok) {
        alert("Objednáno (uloženo do DB)");
      } else {
        alert("Chyba: " + (data && data.error ? data.error : "unknown"));
      }
    })
    .catch(err => {
      alert("Chyba: " + err);
    });
}
