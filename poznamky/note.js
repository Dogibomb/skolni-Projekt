const form = document.getElementById("addNoteForm");
const notesArea = document.getElementById("notesArea");

form.addEventListener("submit", function (e) {
  e.preventDefault();

  const name = document.getElementById("nameInput").value;
  const text = document.getElementById("textInput").value;

  if (name === "" || text === "") return;

  const card = document.createElement("div");
  card.className = "note-card";

  const title = document.createElement("h3");
  title.innerText = "Zákazník: " + name;

  const content = document.createElement("p");
  content.innerText = text;

  const remove = document.createElement("button");
  remove.className = "remove-btn";
  remove.innerText = "Smazat";

  remove.onclick = function () {
    card.remove();
  };

  card.appendChild(title);
  card.appendChild(content);
  card.appendChild(remove);

  notesArea.appendChild(card);
  form.reset();
});