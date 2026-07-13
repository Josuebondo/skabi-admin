// main.js
let versementsData = [];
let currentPage = 1;
const perPage = 5;

// Charger les données
async function load() {
  showLoader();
  try {
    const req = await fetch("/api/versement");
    const data = await req.json();
    versementsData = data; // sauvegarde globale
    populateEntrepotFilter(data);
    renderTable();
  } catch (err) {
    console.error("Erreur lors du chargement des versements:", err);
    const tbody = document.getElementById("tbody");
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-red-600">Impossible de charger les données.</td></tr>`;
  }
}

// Affichage du loader
function showLoader() {
  const tbody = document.getElementById("tbody");
  tbody.innerHTML = `${[...Array(perPage)]
    .map(
      () => `
    <tr class="animate-pulse">
      <td class="px-6 py-4"><div class="h-4 bg-slate-200 rounded w-24"></div></td>
      <td class="px-6 py-4"><div class="h-4 bg-slate-200 rounded w-32"></div></td>
      <td class="px-6 py-4"><div class="h-4 bg-slate-200 rounded w-20"></div></td>
      <td class="px-6 py-4"><div class="h-4 bg-slate-200 rounded w-28"></div></td>
      <td class="px-6 py-4"><div class="h-4 bg-slate-200 rounded w-20 ml-auto"></div></td>
      <td class="px-6 py-4"><div class="h-4 bg-slate-200 rounded w-16 mx-auto"></div></td>
    </tr>
  `,
    )
    .join("")}`;
}

// Remplir le filtre "Entrepôt" dynamiquement
function populateEntrepotFilter(data) {
  const select = document.getElementById("filter-entrepot");
  const selectu = document.getElementById("filter-user");
  const entrepots = [...new Set(data.map((d) => d.entrepot))];
  const user = [...new Set(data.map((d) => d.created_by))];
  entrepots.forEach((e) => {
    const option = document.createElement("option");
    option.value = e;
    option.textContent = e;
    select.appendChild(option);
  });
  user.forEach((u) => {
    const option = document.createElement("option");
    option.value = u;
    option.textContent = u;
    selectu.appendChild(option);
  });
}

// Affichage du tableau avec filtre et pagination
function renderTable() {
  const tbody = document.getElementById("tbody");
  tbody.innerHTML = "";

  // Valeurs des filtres
  const entFilter = document.getElementById("filter-entrepot").value;
  const statutFilter = document.getElementById("filter-statut").value;
  const dateFilter = document.getElementById("filter-date").value;
  const userFilter = document.getElementById("filter-user").value;

  // Filtrer les données
  let filtered = versementsData.filter(
    (d) =>
      (!entFilter || d.entrepot === entFilter) &&
      (!statutFilter || d.statut === statutFilter) &&
      (!userFilter || d.created_by === userFilter) &&
      (!dateFilter || d.date_versement === dateFilter),
  );

  // Pagination
  const totalPages = Math.ceil(filtered.length / perPage) || 1;
  if (currentPage > totalPages) currentPage = totalPages;

  const start = (currentPage - 1) * perPage;
  const pageData = filtered.slice(start, start + perPage);

  // Remplir le tableau
  pageData.forEach((d) => {
    const tr = document.createElement("tr");
    tr.className =
      "hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors";

    let statusClass = "";
    let statusText = d.statut;
    switch (d.statut) {
      case "validé":
        statusClass =
          "bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400";
        break;
      case "en_attente":
        statusClass =
          "bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400";
        statusText = "En attente";
        break;
      case "annulé":
        statusClass =
          "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400";
        break;
    }

    tr.innerHTML = `
      <td class="px-6 py-4 text-sm font-medium">${new Date(d.date_versement).toLocaleDateString("fr-FR", { day: "2-digit", month: "short", year: "numeric" })}</td>
      <td class="px-6 py-4 text-sm">${d.entrepot} (#${d.entrepot_id})</td>
      <td class="px-6 py-4 text-sm">${d.created_by} </td>
      <td class="px-6 py-4 text-sm">${d.reference}</td>
      <td class="px-6 py-4 text-lg font-extrabold text-right">${Number(d.montant).toFixed(2)} $</td>
      <td class="px-6 py-4 text-center"><span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase ${statusClass}">${statusText}</span></td>
    `;
    tbody.appendChild(tr);
  });

  document.getElementById("pageInfo").textContent =
    `Affichage de page ${currentPage} / ${totalPages}`;
}

// Pagination buttons
document.getElementById("prevPage").addEventListener("click", () => {
  if (currentPage > 1) {
    currentPage--;
    renderTable();
  }
});
document.getElementById("nextPage").addEventListener("click", () => {
  const filtered = versementsData; // peut aussi filtrer ici si nécessaire
  const totalPages = Math.ceil(filtered.length / perPage) || 1;
  if (currentPage < totalPages) {
    currentPage++;
    renderTable();
  }
});

// Filters events
["filter-entrepot", "filter-statut", "filter-date", "filter-user"].forEach(
  (id) => {
    document.getElementById(id).addEventListener("change", () => {
      currentPage = 1;
      renderTable();
    });
  },
);

// Bouton reset des filtres
document.getElementById("reset-filters")?.addEventListener("click", () => {
  document.getElementById("filter-entrepot").value = "";
  document.getElementById("filter-statut").value = "";
  document.getElementById("filter-date").value = "";
  document.getElementById("filter-user").value = "";
  currentPage = 1;
  renderTable();
});
const nebtn = document.getElementById("new-btn");
const savebtn = document.getElementById("save-btn");
const cancelbtn = document.getElementById("cancel-btn");
nebtn.addEventListener("click", () => {
  openmodal("new-modal");
});
cancelbtn.addEventListener("click", () => {
  cancel();
});
function openmodal(id) {
  document.getElementById(id).classList.remove("hidden");
}
function Closemodal(id) {
  document.getElementById(id).classList.add("hidden");
}
function cancel() {
  const form = document.getElementById("form");
  form.reset();
  Closemodal("new-modal");
}
const saveBtn = document.getElementById("save-btn");

async function save() {
  const date = document.getElementById("date").value;
  const montant = document.getElementById("montant").value;
  const entrepot = document.getElementById("entrepot").value;

  // 🔹 Activer le loader
  saveBtn.disabled = true;
  const originalContent = saveBtn.innerHTML;
  saveBtn.innerHTML = `
    <svg class="animate-spin h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
    </svg>
    Enregistrement...
  `;

  try {
    const req = await fetch(`/api/versement`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ montant, entrepot, date }),
    });

    const res = await req.json();

    if (res.success) {
      showToast("success", res.message || "Versement créé avec succès !");
      load(); // rafraîchir le tableau
    } else {
      showToast(
        "error",
        res.message || "Erreur lors de la création du versement.",
      );
    }
  } catch (error) {
    console.error(error);
    showToast("error", "Impossible de contacter le serveur.");
  } finally {
    // 🔹 Rétablir le bouton
    saveBtn.disabled = false;
    saveBtn.innerHTML = originalContent;
  }
}
// Fonction simple de toast
function showToast(type, message) {
  // Créer le conteneur si inexistant
  if (!document.getElementById("toast-container")) {
    const container = document.createElement("div");
    container.id = "toast-container";
    container.style.position = "fixed";
    container.style.top = "20px";
    container.style.right = "20px";
    container.style.zIndex = "9999";
    container.style.display = "flex";
    container.style.flexDirection = "column";
    container.style.gap = "10px";
    document.body.appendChild(container);
  }

  const container = document.getElementById("toast-container");

  const toast = document.createElement("div");
  toast.textContent = message;
  toast.style.padding = "10px 20px";
  toast.style.borderRadius = "8px";
  toast.style.color = "#fff";
  toast.style.fontWeight = "bold";
  toast.style.minWidth = "200px";
  toast.style.boxShadow = "0 4px 10px rgba(0,0,0,0.1)";
  toast.style.opacity = "0";
  toast.style.transition = "opacity 0.3s ease";

  if (type === "success") {
    toast.style.backgroundColor = "#16a34a"; // vert
  } else if (type === "error") {
    toast.style.backgroundColor = "#dc2626"; // rouge
  } else {
    toast.style.backgroundColor = "#374151"; // gris
  }

  container.appendChild(toast);

  // Afficher
  requestAnimationFrame(() => {
    toast.style.opacity = "1";
  });

  // Disparaît après 3 secondes
  setTimeout(() => {
    toast.style.opacity = "0";
    toast.addEventListener("transitionend", () => toast.remove());
  }, 3000);
}
savebtn.addEventListener("click", () => {
  save();
});
// Chargement initial
load();
