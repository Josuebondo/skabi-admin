// main.js
import { ApiService } from "./ApiService.js";

const api = new ApiService("https://stock.skabi.shop/");

let mouvements = []; // Données complètes
let filteredMouvements = []; // Données filtrées
let currentPage = 1;
const pageSize = 20; // Nombre de lignes par page

// 🔹 Fetch initial et stockage local
async function initData() {
  mouvements = JSON.parse(localStorage.getItem("mouvements") || "[]");

  if (mouvements.length === 0) {
    console.log("Récupération des données depuis l'API...");
    const response = await api.postData(
      "mouvement/getMouvementsAdminApi",
      { "X-API-KEY": "ADMIN_SECRET_2026" },
      {}, // fetch initial sans filtre
    );

    if (response && response.status === "success") {
      mouvements = response.data;
      localStorage.setItem("mouvements", JSON.stringify(mouvements));
    } else {
      console.error("Erreur fetch initial :", response);
      return;
    }
  }

  // Par défaut, toutes les données
  filteredMouvements = [...mouvements];
  renderPage(1);
}

// 🔹 Filtrage instantané
function filterMouvements(filters = {}) {
  filteredMouvements = mouvements.filter((m) => {
    if (filters.type && m.type !== filters.type) return false;
    if (filters.entrepot_id && m.entrepot_id != filters.entrepot_id)
      return false;
    if (filters.destination_id && m.destination_id != filters.destination_id)
      return false;
    if (filters.article_id && m.article_id != filters.article_id) return false;
    if (
      filters.document &&
      !m.bon_document.toLowerCase().includes(filters.document.toLowerCase())
    )
      return false;
    if (
      filters.date_start &&
      new Date(m.created_at) < new Date(filters.date_start)
    )
      return false;
    if (filters.date_end && new Date(m.created_at) > new Date(filters.date_end))
      return false;
    return true;
  });

  // Reset pagination
  currentPage = 1;
  renderPage(currentPage);
}

// 🔹 Tri dynamique
function sortMouvements(field, asc = true) {
  filteredMouvements.sort((a, b) => {
    let valA = a[field];
    let valB = b[field];

    // Si c'est une date
    if (field.includes("date") || field.includes("created_at")) {
      valA = new Date(valA);
      valB = new Date(valB);
    }

    if (valA < valB) return asc ? -1 : 1;
    if (valA > valB) return asc ? 1 : -1;
    return 0;
  });

  renderPage(currentPage);
}

// 🔹 Pagination locale
function renderPage(page) {
  currentPage = page;
  const start = (page - 1) * pageSize;
  const end = start + pageSize;
  const pageData = filteredMouvements.slice(start, end);
  let source = "";
  let destination = "";
  let qtylement = null;
  const div = document.getElementById("movement-grid");
  div.innerHTML = ""; // Clear previous content
  pageData.forEach((m) => {
    console.log(m);
    if (m.type === "entree") {
      source = m.client | "fournisseur";
      destination = m.entrepot_nom || "Stock";
      qtylement = document.createElement("span");
      qtylement.className = "text-xs font-black text-green-500";
      qtylement.innerHTML = `+${m.quantite} `;
    } else if (m.type === "sortie") {
      source = m.entrepot_nom || "Stock";
      destination = m.client || "client";
      qtylement = document.createElement("span");
      qtylement.className = "text-xs font-black text-red-500";
      qtylement.innerHTML = `-${m.quantite} `;
    } else if (m.type === "transfert") {
      source = m.entrepot_nom || "Stock";
      destination = m.destination_nom || "Stock";
      qtylement = document.createElement("span");
      qtylement.className = "text-xs font-black text-blue-500";
      qtylement.innerHTML = `${m.quantite} `;
    }

    const item = document.createElement("div");
    item.innerHTML = `
            <div class="bg-dark-surface border border-dark-border rounded-lg p-3 hover:border-primary/40 transition-all cursor-pointer group" data-target-section="section-details">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] font-bold text-slate-500 uppercase">${m.created_at} </span>
                                <span class="px-1.5 py-0.5 rounded-md bg-slate-800 text-slate-400 text-[8px] font-mono border border-slate-700">${m.bon_document}</span>
                            </div>
                            <h3 class="text-xs font-bold text-white truncate mb-2 group-hover:text-primary transition-colors">Souris Gaming</h3>
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-1.5 mb-2.5">
                                    <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">${source}</span>
                                    <span class="material-symbols-outlined text-xs text-slate-600">arrow_right_alt</span>
                                    <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">${destination} </span>
                            
                                </div>
                            
                                
                                <span class="px-1.5 py-0.5 rounded-md bg-slate-800 text-slate-400 text-[8px] font-mono border border-slate-700">${m.type}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                ${qtylement.outerHTML}
                                <span class="material-icons text-slate-600 text-sm group-hover:translate-x-0.5 transition-transform">chevron_right</span>
                            </div>
                        </div>
 
        `;

    item.addEventListener("click", () => {
      // Afficher les détails du mouvement dans la section dédiée
      const detailsSection = document.getElementById("section-details");
      el("mvn-id").textContent = m.id;
      el("doc-id").textContent = m.bon_document;
      el("date").textContent = new Date(m.updated_at).toLocaleString();
      el("user").textContent = m.updated_by || "Inconnu";
      el("creatdate").textContent = new Date(m.created_at).toLocaleString();
      el("auteur").textContent = m.created_by || "Inconnu";

      if (m.statut == "validé") {
        el("status").className = "text-xs font-bold text-accent-emerald";
        m.statu = "Approuvé";
      } else if (m.statut == "en_attente") {
        el("status").className = "text-xs font-bold text-slate-500";
        m.statu = "En Attente";
      } else if (m.statut == "annulé") {
        el("status").className = "text-xs font-bold text-accent-coral";
        m.statu = "Rejeté";
      }
      el("status").textContent = m.statu || "Inconnu";
      if (m.type === "entree") {
        el("qty").textContent = `+${m.quantite}`;
        el("qty").classList.add("text-accent-emerald");
        el("qty-icon").textContent = "add_circle_outline";
        el("qty-icon").classList.add("text-accent-coral");
      } else if (m.type === "sortie") {
        el("qty").textContent = `-${m.quantite}`;
        el("qty").classList.add("text-accent-coral");
        el("qty-icon").textContent = "remove_circle_outline";
        el("qty-icon").classList.add("text-accent-coral");
      } else {
        el("qty").textContent = m.quantite;
        el("qty").classList.add("text-blue-500");
        el("qty-icon").textContent = "swap_horiz";
        el("qty-icon").classList.add("text-blue-500");
      }
      el("source").textContent = source;
      el("destination").textContent = destination;

      if (m.type === "entree") {
        el("mvn-type").textContent = "Type de Mouvement: Entrée";
        el("mvn-type").className = "text-lg font-bold text-green-500";
      } else if (m.type === "sortie") {
        el("mvn-type").textContent = "Type de Mouvement: Sortie";
        el("mvn-type").className = "text-lg font-bold text-red-500";
      } else if (m.type === "transfert") {
        el("mvn-type").textContent = "Type de Mouvement: Transfert";
        el("mvn-type").className = "text-lg font-bold text-blue-500";
      }
      // Afficher la section des détails
      detailsSection.classList.remove("hidden");
      detailsSection.scrollIntoView({ behavior: "smooth" });
    });

    div.appendChild(item);
    createPagination();
  });

  // Ici tu peux mettre le code pour afficher les données dans ton tableau HTML
}
function el(id) {
  return document.getElementById(id);
}
let btnNext = document.getElementById("next");
let btnPrev = document.getElementById("prev");
btnNext.addEventListener("click", nextPage);
btnPrev.addEventListener("click", prevPage);

function createPagination() {
  const paginationDiv = document.getElementById("pagination");
  paginationDiv.innerHTML = ""; // Clear previous pagination
  const totalPages = Math.ceil(filteredMouvements.length / pageSize);
  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("button");
    btn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-[10px] font-bold ${i === currentPage ? "bg-primary text-white" : "border border-slate-700 hover:bg-slate-800 text-slate-500"}`;
    btn.innerText = i;
    btn.addEventListener("click", () => renderPage(i));
    paginationDiv.appendChild(btn);
  }
}

// 🔹 Navigation pagination
function nextPage() {
  if (currentPage * pageSize < filteredMouvements.length) {
    renderPage(currentPage + 1);
  }
}

function prevPage() {
  if (currentPage > 1) {
    renderPage(currentPage - 1);
  }
}

// 🔹 Rafraîchir les données depuis l'API
async function refreshData() {
  localStorage.removeItem("mouvements");
  await initData();
}

// 🔹 Initialisation
initData();

// 🔹 Exemple d'utilisation des filtres
// filterMouvements({ type: "sortie", entrepot_id: 1, date_start: "2026-02-01" });
// sortMouvements("created_at", false);

document.getElementById("refresh-btn").addEventListener("click", async () => {
  const btn = document.getElementById("refresh-btn");
  const icon = document.getElementById("refresh-icon");
  const text = document.getElementById("refresh-text");

  // ✅ Afficher le spinner
  icon.textContent = "autorenew"; // icône de rotation
  icon.classList.add("spin");
  text.textContent = "Actualisation...";

  try {
    // Ici tu appelles ta fonction pour récupérer les mouvements
    await refreshData(); // fonction fetch ou API

    // Petite pause pour que l’utilisateur voit le spinner
    await new Promise((r) => setTimeout(r, 500));
  } catch (err) {
    console.error("Erreur lors de la récupération des mouvements :", err);
  } finally {
    // ✅ Revenir au bouton normal
    icon.textContent = "autorenew";
    icon.classList.remove("spin");
    text.textContent = "Actualiser";
  }
});
