const modal = document.getElementById("new-doc-modal");
const openModalBtn = document.getElementById("new-doc-btn");
const closeModalBtn = document.getElementById("closeModalBtn");

openModalBtn.addEventListener("click", () => {
  modal.classList.remove("hidden");
});

closeModalBtn.addEventListener("click", () => {
  modal.classList.add("hidden");
});

// Fermer le modal en cliquant en dehors de celui-ci
window.addEventListener("click", (event) => {
  if (event.target === modal) {
    modal.classList.add("hidden");
  }
});

//fermer le modal avec la touche "Escape"
window.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    modal.classList.add("hidden");
  }
});
