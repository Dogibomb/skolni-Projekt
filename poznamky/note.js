const form        = document.getElementById("addNoteForm");
const notesArea   = document.getElementById("notesArea");
const select      = document.getElementById("customerSelect");
const groupWith   = document.getElementById("groupWithOrders");
const groupNo     = document.getElementById("groupNoOrders");

function loadData() {
  fetch("list_notes.php")
    .then(r => r.json())
    .then(data => {
      if (!data.ok) {
        notesArea.innerHTML = "<p style='color:red'>Chyba: " + (data.error ?? "unknown") + "</p>";
        return;
      }

      groupWith.innerHTML = "";
      (data.customersWithOrders ?? []).forEach(c => {
        const opt = document.createElement("option");
        opt.value = c.id;
        opt.textContent = c.name + (c.email ? " (" + c.email + ")" : "");
        groupWith.appendChild(opt);
      });

      groupNo.innerHTML = "";
      (data.customersNoOrders ?? []).forEach(c => {
        const opt = document.createElement("option");
        opt.value = c.id;
        opt.textContent = c.name + (c.email ? " (" + c.email + ")" : "");
        groupNo.appendChild(opt);
      });

      renderNotes(data.notes ?? []);
    })
    .catch(err => {
      notesArea.innerHTML = "<p style='color:red'>Chyba: " + err + "</p>";
    });
}

// Vykreslí pole poznámek
function renderNotes(notes) {
  if (notes.length === 0) {
    notesArea.innerHTML = "<p style='color:#888; margin-top:10px;'>Zatím žádné poznámky.</p>";
    return;
  }

  notesArea.innerHTML = "";
  notes.forEach(n => {
    const card = createNoteCard(n);
    notesArea.appendChild(card);
  });
}

// Vytvoří DOM element poznámky
function createNoteCard(n) {
  const card = document.createElement("div");
  card.className = "note-card";
  card.dataset.id = n.id;

  const title = document.createElement("h3");
  title.textContent = "Zákazník: " + n.customer_name;

  const email = document.createElement("p");
  email.className = "note-email";
  email.textContent = n.customer_email ?? "";

  const content = document.createElement("p");
  content.textContent = n.text;

  const meta = document.createElement("span");
  meta.className = "note-meta";
  // Formátuj datum pokud existuje
  if (n.created_at) {
    const d = new Date(n.created_at);
    meta.textContent = d.toLocaleString("cs-CZ");
  }

  const remove = document.createElement("button");
  remove.className = "remove-btn";
  remove.textContent = "Smazat";
  remove.onclick = function () {
    deleteNote(n.id, card);
  };

  card.appendChild(title);
  if (n.customer_email) card.appendChild(email);
  card.appendChild(content);
  card.appendChild(meta);
  card.appendChild(remove);

  return card;
}

// Odešle novou poznámku do DB
form.addEventListener("submit", function (e) {
  e.preventDefault();

  const user_id = select.value;
  const text    = document.getElementById("textInput").value.trim();

  if (!user_id || text === "") return;

  const btn = document.getElementById("addBtn");
  btn.disabled = true;

  fetch("save_note.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ user_id: parseInt(user_id), text })
  })
    .then(r => r.json())
    .then(data => {
      btn.disabled = false;
      if (!data.ok) {
        alert("Chyba: " + (data.error ?? "unknown"));
        return;
      }

      const selectedOpt = select.options[select.selectedIndex];
      const fullText = selectedOpt.textContent;
      const emailMatch = fullText.match(/\(([^)]+)\)/);
      const customerName  = fullText.replace(/\s*\([^)]*\)/, "").trim();
      const customerEmail = emailMatch ? emailMatch[1] : "";

      const noteObj = {
        id: data.id,
        user_id: parseInt(user_id),
        customer_name:  customerName,
        customer_email: customerEmail,
        text,
        created_at: data.created_at,
      };

      const card = createNoteCard(noteObj);
      if (notesArea.querySelector("p")) {
        notesArea.innerHTML = "";
      }
      notesArea.insertBefore(card, notesArea.firstChild);

      form.reset();
    })
    .catch(err => {
      btn.disabled = false;
      alert("Chyba: " + err);
    });
});

// Smaže poznámku z DB a odstraní kartu z DOM
function deleteNote(id, card) {
  fetch("save_note.php", {
    method: "DELETE",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  })
    .then(r => r.json())
    .then(data => {
      if (!data.ok) {
        alert("Chyba při mazání: " + (data.error ?? "unknown"));
        return;
      }
      card.remove();
      if (notesArea.children.length === 0) {
        notesArea.innerHTML = "<p style='color:#888; margin-top:10px;'>Zatím žádné poznámky.</p>";
      }
    })
    .catch(err => alert("Chyba: " + err));
}

loadData();
