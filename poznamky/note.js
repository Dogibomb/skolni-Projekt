const form        = document.getElementById("addNoteForm");
const notesArea   = document.getElementById("notesArea");
const select      = document.getElementById("customerSelect");
const searchInput = document.getElementById("customerSearch");

let allNotes = [];

// nacte zakazniky a poznamky ze serveru
function loadData(search = "") {
  const url = "list_notes.php" + (search ? "?search=" + encodeURIComponent(search) : "");

  fetch(url)
    .then(r => r.json())
    .then(data => {
      if (!data.ok) {
        notesArea.innerHTML = "<p style='color:red'>Chyba: " + (data.error ?? "unknown") + "</p>";
        return;
      }

      // naplni select zakazniky (ti s objednavkou)
      const previousValue = select.value;
      select.innerHTML = '<option value="">— Vyber zákazníka —</option>';

      (data.customers ?? []).forEach(c => {
        const opt = document.createElement("option");
        opt.value = c.id;
        opt.textContent = c.name + (c.email ? " (" + c.email + ")" : "");
        select.appendChild(opt);
      });

      // obnov vybranou hodnotu pokud jeste existuje
      if (previousValue) select.value = previousValue;

      // uloz vsechny poznamky a vykresli (filtrovane podle search)
      allNotes = data.notes ?? [];
      renderFilteredNotes(search);
    })
    .catch(err => {
      notesArea.innerHTML = "<p style='color:red'>Chyba: " + err + "</p>";
    });
}

// vykresli poznamky filtrovane podle search textu
function renderFilteredNotes(search = "") {
  const q = search.toLowerCase().trim();
  const filtered = q
    ? allNotes.filter(n =>
        (n.customer_name ?? "").toLowerCase().includes(q) ||
        (n.customer_email ?? "").toLowerCase().includes(q) ||
        (n.text ?? "").toLowerCase().includes(q)
      )
    : allNotes;
  renderNotes(filtered);
}

// vyhledavani zakazniku pri psani s debounce
let searchTimeout;
searchInput.addEventListener("input", function () {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    const q = searchInput.value.trim();
    loadData(q);
  }, 300);
});

// vykresli pole poznamek
function renderNotes(notes) {
  if (notes.length === 0) {
    notesArea.innerHTML = "<p class='no-notes'>Zatím žádné poznámky.</p>";
    return;
  }
  notesArea.innerHTML = "";
  notes.forEach(n => {
    notesArea.appendChild(createNoteCard(n));
  });
}

// vytvori DOM element poznamky
function createNoteCard(n) {
  const card = document.createElement("div");
  card.className = "note-card";
  card.dataset.id = n.id;

  const header = document.createElement("div");
  header.className = "note-card-header";

  const title = document.createElement("h3");
  title.textContent = n.customer_name;

  const meta = document.createElement("span");
  meta.className = "note-meta";
  if (n.created_at) {
    const d = new Date(n.created_at);
    meta.textContent = d.toLocaleString("cs-CZ");
  }

  header.appendChild(title);
  header.appendChild(meta);

  const email = document.createElement("p");
  email.className = "note-email";
  email.textContent = n.customer_email ?? "";

  const content = document.createElement("p");
  content.className = "note-text";
  content.textContent = n.text;

  const remove = document.createElement("button");
  remove.className = "remove-btn";
  remove.textContent = "Smazat";
  remove.onclick = function () {
    deleteNote(n.id, card);
  };

  card.appendChild(header);
  if (n.customer_email) card.appendChild(email);
  card.appendChild(content);
  card.appendChild(remove);

  return card;
}

// odesle novou poznamku do DB
form.addEventListener("submit", function (e) {
  e.preventDefault();

  const user_id = select.value;
  const text = document.getElementById("textInput").value.trim();

  if (!user_id || text === "") return;

  const btn = document.getElementById("addBtn");
  btn.disabled = true;
  btn.textContent = "Ukládám…";

  fetch("save_note.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ user_id: parseInt(user_id), text })
  })
    .then(r => r.json())
    .then(data => {
      btn.disabled = false;
      btn.textContent = "Přidat poznámku";
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

      // pridej na zacatek allNotes a vykresli
      allNotes.unshift(noteObj);
      const card = createNoteCard(noteObj);
      if (notesArea.querySelector("p")) {
        notesArea.innerHTML = "";
      }
      notesArea.insertBefore(card, notesArea.firstChild);

      // reset jen textarea a select, ne search
      document.getElementById("textInput").value = "";
      select.value = "";
    })
    .catch(err => {
      btn.disabled = false;
      btn.textContent = "Přidat poznámku";
      alert("Chyba: " + err);
    });
});

// smaze poznamku z DB a z DOMu
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
      allNotes = allNotes.filter(n => n.id !== id);
      card.remove();
      if (notesArea.children.length === 0) {
        notesArea.innerHTML = "<p class='no-notes'>Zatím žádné poznámky.</p>";
      }
    })
    .catch(err => alert("Chyba: " + err));
}

loadData();
