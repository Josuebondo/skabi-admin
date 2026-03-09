// main.js
import { ApiService } from "./ApiService.js";

const api = new ApiService("https://stock.skabi.shop/");

let mouvements = []; // Données complètes
let filteredMouvements = []; // Données filtrées
let bons = [];
let filteredBons = [];

let currentPage = 1;
const pageSize = 20; // Nombre de lignes par page
function showToast(message, type = "info") {
  const container = document.getElementById("toast-container");

  const toast = document.createElement("div");

  const baseStyle =
    "flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-semibold transform transition-all duration-300 translate-x-full opacity-0";

  const types = {
    success: "bg-emerald-600 text-white",
    error: "bg-red-600 text-white",
    info: "bg-slate-800 text-white border border-slate-700",
    warning: "bg-yellow-500 text-black",
  };

  toast.className = `${baseStyle} ${types[type] || types.info}`;

  const icon = document.createElement("span");
  icon.className = "material-icons text-lg";

  if (type === "success") icon.textContent = "check_circle";
  else if (type === "error") icon.textContent = "error";
  else if (type === "warning") icon.textContent = "warning";
  else icon.textContent = "info";

  const text = document.createElement("span");
  text.textContent = message;

  toast.appendChild(icon);
  toast.appendChild(text);
  container.appendChild(toast);

  // 🔥 Animation entrée
  setTimeout(() => {
    toast.classList.remove("translate-x-full", "opacity-0");
  }, 50);

  // 🔥 Auto remove après 3 secondes
  setTimeout(() => {
    toast.classList.add("translate-x-full", "opacity-0");
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}
// 🔹 Fetch initial et stockage local
async function initData() {
  mouvements = JSON.parse(localStorage.getItem("mouvements") || "[]");
  bons = JSON.parse(localStorage.getItem("bons") || "[]");
  if (bons.length === 0) {
    console.log("Récupération des données depuis l'API...");
    const response = await api.postData(
      "mouvement/getBonsAdminApi",
      { "X-API-KEY": "ADMIN_SECRET_2026" },
      {}, // fetch initial sans filtre
    );

    if (response && response.status === "success") {
      bons = response.data;
      localStorage.setItem("bons", JSON.stringify(bons));
    } else {
      console.error("Erreur fetch initial :", response);
      return;
    }
  }
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
  filteredBons = [...bons];
  renderPage(1);
  renderBons(1);
  console.log("Mouvements chargés :", mouvements, "bons chargés :", bons);
  // console.log(bons);
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
    if (filters.statut && m.statut !== filters.statut) return false;
    return true;
  });

  // console.log("Mouvements après filtrage :", filteredMouvements);
  // Reset pagination
  currentPage = 1;
  renderPage(currentPage);
  renderBons(currentPage);
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
  let typeelement = null;
  let statutElement = null;
  const div = document.getElementById("movement-grid");
  div.innerHTML = ""; // Clear previous content
  if (pageData.length === 0) {
    div.innerHTML =
      '<div class="col-span-full text-center text-slate-500 py-10">Aucun mouvement trouvé pour ces critères.</div>';
    return;
  }
  pageData.forEach((m) => {
    // console.log(m);
    if (m.type === "entrée") {
      source = m.client | "fournisseur";
      destination = m.entrepot_nom || "Stock";
      qtylement = document.createElement("span");
      qtylement.className = "text-xs font-black text-green-500";
      qtylement.innerHTML = `+${m.quantite} `;
      typeelement = document.createElement("span");
      typeelement.className = "text-xs font-bold text-green-500";
      typeelement.innerHTML = "Entrée";
    } else if (m.type === "sortie") {
      source = m.entrepot_nom || "Stock";
      destination = m.client || "client";
      qtylement = document.createElement("span");
      qtylement.className = "text-xs font-black text-red-500";
      qtylement.innerHTML = `-${m.quantite} `;
      typeelement = document.createElement("span");
      typeelement.className =
        "px-1.5 py-0.5 rounded-md bg-slate-800 text-[8px] font-mono border border-slate-700 font-bold text-red-500";
      typeelement.innerHTML = "Sortie";
    } else if (m.type === "transfert") {
      source = m.entrepot_nom || "Stock";
      destination = m.destination_nom || "Stock";
      qtylement = document.createElement("span");
      qtylement.className =
        "px-1.5 py-0.5 rounded-md bg-slate-800 text-[8px] font-mono border border-slate-700 font-black text-blue-500";
      qtylement.innerHTML = `${m.quantite} `;
      typeelement = document.createElement("span");
      typeelement.className =
        "px-1.5 py-0.5 rounded-md bg-slate-800 text-[8px] font-mono border border-slate-700 font-bold text-blue-500";
      typeelement.innerHTML = "Transfert";
    } else {
      source = m.entrepot_nom || "Stock";
      destination = m.destination_nom || "Stock";
      qtylement = document.createElement("span");
      qtylement.className = "text-xs font-black text-gray-500";
      qtylement.innerHTML = `${m.quantite} `;
      typeelement = document.createElement("span");
      typeelement.className =
        " px-1.5 py-0.5 rounded-md bg-slate-800 text-slate-400 text-[8px] font-mono border border-slate-700";
      typeelement.innerHTML = m.type || "Inconnu";
    }
    if (m.statut == "validé") {
      statutElement = document.createElement("span");
      statutElement.className =
        "text-xs font-bold text-accent-emerald border border-accent-emerald/40 px-1.5 py-0.5 rounded-md";
      statutElement.innerHTML = "Validé";
    } else if (m.statut == "en_attente") {
      statutElement = document.createElement("span");
      statutElement.className =
        "text-xs font-bold text-slate-500 border border-slate-500/40 px-1.5 py-0.5 rounded-md";
      statutElement.innerHTML = "En Attente";
    } else if (m.statut == "annulé") {
      statutElement = document.createElement("span");
      statutElement.className =
        "text-xs font-bold text-accent-coral border border-accent-coral/40 px-1.5 py-0.5 rounded-md";
      statutElement.innerHTML = "Annulé";
    } else {
      statutElement = document.createElement("span");
      statutElement.className =
        "text-xs font-bold text-gray-500 border border-gray-500/40 px-1.5 py-0.5 rounded-md";
      statutElement.innerHTML = m.statut || "Inconnu";
    }

    const item = document.createElement("div");
    item.innerHTML = `
            <div class="bg-dark-surface border border-dark-border rounded-lg p-3 hover:border-primary/40 transition-all cursor-pointer group" data-target-section="section-details">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] font-bold text-slate-500 uppercase">${m.created_at} </span>
                                <span class="px-1.5 py-0.5 rounded-md bg-slate-800 text-slate-400 text-[8px] font-mono border border-slate-700">${m.bon_document}</span>
                            </div>
                            <h3 class="text-xs font-bold text-white truncate mb-2 group-hover:text-primary transition-colors">${m.article_id} - ${m.article_nom} </h3>
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-1.5 mb-2.5">
                                    <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">${source}</span>
                                    <span class="material-symbols-outlined text-xs text-slate-600">arrow_right_alt</span>
                                    <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">${destination} </span>
                            
                                </div>
                            
                                
                                ${typeelement.outerHTML}
                                
                            </div>
                            <div class="flex items-center justify-between">
                                ${qtylement.outerHTML}
                               
                                
                              <div class="flex items-center justify-between">
                                  ${statutElement.outerHTML}
                                   
                              </div>
                            </div>
                            
                        </div>
 
        `;

    item.addEventListener("click", () => showdetails(m));

    div.appendChild(item);
    createPagination();
  });

  // Ici tu peux mettre le code pour afficher les données dans ton tableau HTML
}

function renderBons(page) {
  currentPage = page;
  const start = (page - 1) * pageSize;
  const end = start + pageSize;
  const pageData = filteredBons.slice(start, end);
  const div = document.getElementById("documents-list");
  div.innerHTML = "";

  if (filteredBons.length === 0) {
    div.innerHTML =
      '<div class="col-span-full text-center text-slate-500 py-10">Aucun bon trouvé pour ces critères.</div>';
    return;
  }

  pageData.forEach((b) => {
    const item = document.createElement("div");

    // === Création bouton correctement ===
    const showbtn = document.createElement("button");
    showbtn.className =
      "text-[10px] font-bold text-primary hover:underline flex items-center gap-1";
    showbtn.innerHTML = `
      VOIR DÉTAILS 
      <span class="material-icons text-xs">open_in_new</span>
    `;

    showbtn.addEventListener("click", () => {
      ShowbonDetails(b);
      // console.log("Détails du bon affichés pour :", b);²
    });

    let source = "";
    let destination = "";

    if (b.type === "entrée") {
      source = b.client || "Fournisseur";
      destination = b.entrepot?.nom || "Stock";
    } else if (b.type === "sortie") {
      source = b.entrepot?.nom || "Stock";
      destination = b.client || "Client";
    } else if (b.type === "transfert") {
      source = b.entrepot?.nom || "Stock";
      destination = b.destination?.nom || "Stock";
    }
    const statuscontainer = document.createElement("p");
    // ⚡ Mise à jour du statut
    if (b.statut === "validé") {
      statuscontainer.innerHTML = `
                                 <span class="text-xs font-bold text-accent-emerald"> Validé</span>`;
    } else if (b.statut === "en_attente") {
      statuscontainer.innerHTML = `
                                 <span class="text-xs font-bold text-slate-500"> En attente</span>`;
    } else if (b.statut === "annulé") {
      statuscontainer.innerHTML = `
                                 <span class="text-xs font-bold text-accent-coral"> Annulé</span>`;
    }

    item.innerHTML = `
      <div class="bg-dark-surface max-h-[150px] border border-dark-border rounded-xl p-4 hover:border-slate-500 transition-all">
        <div class="flex items-center justify-between border-b border-dark-border pb-3 mb-3">
         <div class="flex flex-col">
          <h4 class="text-sm font-bold text-white">Document ${b.document}</h4>
          <h4 class="text-sm font-madium text-slate-400">Date: ${b.created_at}</h4>
         </div>
         <div class="flex flex-col">
       
         <span class="text-xs text-slate-400">${b.mouvements.length} Articles</span>
         ${statuscontainer.outerHTML}
         </div>
        </div>

        <div class="grid grid-cols-4 gap-4">
          <div class="text-[10px]">
            <span class="text-slate-500 block">Origine</span>
            <span class="text-slate-200  truncate max-w-[30px] font-bold">${source}</span>
          </div>
          <div class="text-[10px]">
            <span class="text-slate-500 block">Destination</span>
            <span class="text-slate-200  truncate max-w-[30px] font-bold">${destination}</span>
          </div>
          <div class="text-[10px]">
            <span class="text-slate-500 block">Crée par</span>
            <span class="text-slate-200  truncate max-w-[50px] font-bold">${b.createur || "-"}</span>
          </div>
          <div class="flex justify-end" id="btn-container"></div>
        </div>
      </div>
    `;

    // Ajouter bouton au bon endroit
    item.querySelector("#btn-container").appendChild(showbtn);

    div.appendChild(item);
    createBPagination();
  });
}

function ShowbonDetails(b) {
  const detailsSection = el("section-document-details");
  const documentsSection = el("section-documents");
  const statuscontainer = el("b-status-contaner");
  const itemsContainer = el("b-items");

  // ⚡ Mise à jour du statut
  if (b.statut === "validé") {
    statuscontainer.innerHTML = `<span class="w-2 h-2 rounded-full bg-accent-emerald"></span>
                                 <span class="text-xs font-bold text-accent-emerald"> Validé</span>`;
  } else if (b.statut === "en_attente") {
    statuscontainer.innerHTML = `<span class="w-2 h-2 rounded-full bg-slate-500"></span>
                                 <span class="text-xs font-bold text-slate-500"> En attente</span>`;
  } else if (b.statut === "annulé") {
    statuscontainer.innerHTML = `<span class="w-2 h-2 rounded-full bg-accent-coral"></span>
                                 <span class="text-xs font-bold text-accent-coral"> Annulé</span>`;
  }

  // ⚡ Détermination source et destination
  let source, destination;
  if (b.type === "entrée") {
    source = b.client || "Fournisseur";
    destination = b.entrepot?.nom || "Stock";
  } else if (b.type === "sortie") {
    source = b.entrepot?.nom || "Stock";
    destination = b.client || "Client";
  } else if (b.type === "transfert") {
    source = b.entrepot?.nom || "Stock";
    destination = b.destination?.nom || "Stock";
  } else {
    source = b.entrepot?.nom || "Stock";
    destination = b.destination?.nom || "Stock";
  }

  // ⚡ Remplir les infos générales
  el("bon-doc").textContent = b.document;
  el("bon-type").textContent = b.type;
  el("bon-id").textContent = b.id;
  el("bon-createur").textContent = b.createur || "Inconnu";
  el("bon-date").textContent = b.created_at || "Inconnu";
  el("bon-qty").textContent = b.mouvements.length || "0";
  el("bon-source").textContent = source || "Inconnu";
  el("bon-destination").textContent = destination || "Inconnu";
  el("b-validateur").innerText = b.validateur || "Inconnu";
  el("b-v-date").innerText = b.validated_at || "Inconnu";

  // ⚡ Générer dynamiquement la liste des articles
  itemsContainer.innerHTML = ""; // Vider l'ancien contenu
  b.mouvements.map((m) => {
    let qty = m.quantite;
    let colorClass = "text-slate-300"; // neutre par défaut

    if (b.type === "sortie") {
      qty = -Math.abs(qty); // négatif pour sortie
      colorClass = "text-accent-coral font-black";
    } else if (b.type === "entrée") {
      qty = Math.abs(qty); // positif pour entrée
      colorClass = "text-accent-emerald font-black";
    } else if (b.type === "transfert") {
      colorClass = "text-slate-500 font-black"; // neutre
    }

    const div = document.createElement("div");
    div.className = "flex items-center justify-between text-xs";
    div.innerHTML = `
      <span class="text-slate-300 font-semibold">${m.article_id}-${m.article_nom}</span>
      <span class="${colorClass}">${qty}</span>
    `;
    itemsContainer.appendChild(div);
  });

  // ⚡ Masquer la liste des documents et afficher la section détail
  documentsSection.classList.add("hidden");
  detailsSection.classList.remove("hidden");
}
function getmovement(id) {
  return mouvements.find((m) => m.id == id);
}
function showdetails(m) {
  // Afficher les détails du mouvement dans la section dédiée
  const detailsSection = document.getElementById("section-details");
  let source = "";
  let destination = "";
  if (m.type === "entrée") {
    source = m.client | "fournisseur";
    destination = m.entrepot_nom || "Stock";
  } else if (m.type === "sortie") {
    source = m.entrepot_nom || "Stock";
    destination = m.client || "client";
  } else if (m.type === "transfert") {
    source = m.entrepot_nom || "Stock";
    destination = m.destination_nom || "Stock";
  } else {
    source = m.entrepot_nom || "Stock";
    destination = m.destination_nom || "Stock";
  }
  el("mvn-id").textContent = m.id;
  el("doc-id").textContent = m.bon_document;
  el("date").textContent = new Date(m.updated_at).toLocaleString();
  el("user").textContent = m.updated_by || "Inconnu";
  el("creatdate").textContent =
    new Date(m.created_at).toLocaleString() || "Inconnu";
  el("auteur").textContent = m.createur_nom || "Inconnu";
  el("validateur").textContent = m.validateur_nom || "Inconnu";
  el("v-date").textContent = m.date_validation
    ? new Date(m.date_validation).toLocaleString()
    : "Inconnu";

  el("note").textContent = m.note || "Aucune note";

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
  if (m.type === "entrée") {
    el("qty").textContent = `+${m.quantite}`;
    el("qty").className = "text-green-500";
    el("qty-icon").textContent = "add_circle_outline";
    el("qty-icon").className =
      "material-icons  text-sm mt-0.5 text-accent-emerald";
  } else if (m.type === "sortie") {
    el("qty").textContent = `-${m.quantite}`;
    el("qty").className = "text-red-500";
    el("qty-icon").textContent = "remove_circle_outline";
    el("qty-icon").className =
      "material-icons  text-sm mt-0.5 text-accent-coral";
  } else {
    el("qty").textContent = m.quantite;
    el("qty").className = "text-blue-500";
    el("qty-icon").textContent = "swap_horiz";
    el("qty-icon").className = "material-icons  text-sm mt-0.5 text-blue-500";
  }
  el("source").textContent = source;
  el("destination").textContent = destination;

  if (m.type === "entrée") {
    el("mvn-type").textContent = "Type de Mouvement: Entrée";
    el("mvn-type").className = "text-lg font-bold text-green-500";
  } else if (m.type === "sortie") {
    el("mvn-type").textContent = "Type de Mouvement: Sortie";
    el("mvn-type").className = "text-lg font-bold text-red-500";
  } else if (m.type === "transfert") {
    el("mvn-type").textContent = "Type de Mouvement: Transfert";
    el("mvn-type").className = "text-lg font-bold text-blue-500";
  }
  el("article-nom").textContent = m.article_nom || "N/A";
  el("sku").textContent = m.article_id || "N/A";
  // Afficher la section des détails
  detailsSection.classList.remove("hidden");
  detailsSection.scrollIntoView({ behavior: "smooth" });
}
function el(id) {
  return document.getElementById(id);
}
// 🔹 Navigation pagination

let btnNext = document.getElementById("next");
let btnPrev = document.getElementById("prev");
btnNext.addEventListener("click", nextPage);
btnPrev.addEventListener("click", prevPage);

let bbtnNext = document.getElementById("bnext");
let bbtnPrev = document.getElementById("bprev");
bbtnNext.addEventListener("click", bnextPage);
bbtnPrev.addEventListener("click", bprevPage);

function createPagination() {
  const paginationDiv = document.getElementById("pagination");
  paginationDiv.innerHTML = "";

  const totalPages = Math.ceil(filteredMouvements.length / pageSize);
  const maxVisible = 5;

  let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
  let end = start + maxVisible - 1;

  if (end > totalPages) {
    end = totalPages;
    start = Math.max(1, end - maxVisible + 1);
  }

  for (let i = start; i <= end; i++) {
    const btn = document.createElement("button");
    btn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-[10px] font-bold ${
      i === currentPage
        ? "bg-primary text-white"
        : "border border-slate-700 hover:bg-slate-800 text-slate-500"
    }`;
    btn.innerText = i;
    btn.addEventListener("click", () => renderPage(i));
    paginationDiv.appendChild(btn);
  }
}
function createBPagination() {
  const paginationDiv = document.getElementById("bpagination");
  paginationDiv.innerHTML = "";

  const totalPages = Math.ceil(filteredBons.length / pageSize);
  const maxVisible = 5;

  let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
  let end = start + maxVisible - 1;

  if (end > totalPages) {
    end = totalPages;
    start = Math.max(1, end - maxVisible + 1);
  }

  for (let i = start; i <= end; i++) {
    const btn = document.createElement("button");
    btn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-[10px] font-bold ${
      i === currentPage
        ? "bg-primary text-white"
        : "border border-slate-700 hover:bg-slate-800 text-slate-500"
    }`;
    btn.innerText = i;
    btn.addEventListener("click", () => renderBons(i));
    paginationDiv.appendChild(btn);
  }
}

function nextPage() {
  if (currentPage * pageSize < filteredMouvements.length) {
    renderPage(currentPage + 1);
  }
}
function bnextPage() {
  if (currentPage * pageSize < filteredBons.length) {
    renderBons(currentPage + 1);
  }
}

function bprevPage() {
  if (currentPage > 1) {
    renderBons(currentPage - 1);
  }
}
function prevPage() {
  if (currentPage > 1) {
    renderPage(currentPage - 1);
  }
}
let dateActualisation = localStorage.getItem("dateActualisation")
  ? new Date(localStorage.getItem("dateActualisation"))
  : new Date();

el("dateActualisation").innerText = dateActualisation.toLocaleTimeString();
// 🔹 Rafraîchir les données depuis l'API
async function refreshData() {
  localStorage.removeItem("mouvements");
  localStorage.removeItem("bons");
  localStorage.setItem("dateActualisation", new Date().toISOString());

  el("dateActualisation").textContent = dateActualisation.toLocaleTimeString();
  await initData();
}

// 🔹 Initialisation
initData();
// 🔹 Filtrage rapide par période
// 🔹 Filtrage rapide par période avec loader amélioré
const dateFilter = document.getElementById("date-filter");

// Créer un loader une seule fois
let dateLoader = el("date-loader");

const dateText = el("date-text-content");

dateFilter.addEventListener("change", async (e) => {
  if (e.target.value === "") {
    // Si "Aucune période" est sélectionnée, réinitialiser les filtres
    filterMouvements(); // Affiche tous les mouvements
    dateText.textContent = "Filtrer par date";
    return;
  }
  const days = parseInt(e.target.value);
  const now = new Date();
  const startDate = new Date(now.getTime() - days * 24 * 60 * 60 * 1000);

  // ✅ Afficher loader
  dateLoader.classList.remove("hidden");
  dateLoader.classList.add("spin");
  dateText.textContent = "Chargement...";

  // ✅ Filtrer les mouvements
  await new Promise((resolve) => setTimeout(resolve, 1000)); // optionnel: mini délai pour le spinner
  filterMouvements({
    date_start: startDate.toISOString(),
    date_end: now.toISOString(),
  });

  // ✅ Masquer loader et remettre texte normal
  dateLoader.classList.add("hidden");
  dateLoader.classList.remove("spin");
  if (days === 7)
    dateText.textContent = "Affichage des mouvements  7 derniers jours";
  else if (days === 1)
    dateText.textContent = "Affichage des mouvements  24 derniers heures";
  else if (days === 30)
    dateText.textContent = "Affichage des mouvements  30 derniers jours";
  else if (days === 90)
    dateText.textContent = "Affichage des mouvements  90 derniers jours";
  else if (days === 180)
    dateText.textContent = "Affichage des mouvements  180 derniers jours";
  else if (days === 365)
    dateText.textContent = "Affichage des mouvements  365 derniers jours";
  else dateText.textContent = "Filtrer par date";
});
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

//supression & validation

async function deleteMouvement(id) {
  // if (!confirm("Êtes-vous sûr de vouloir supprimer ce mouvement ?")) return;

  el("delete-btn").disabled = true;
  el("d-btn-icon").textContent = "autorenew";
  el("d-btn-icon").classList.add("spin");
  el("d-btntxt").textContent = "Supression...";
  // let userid = JSON.parse(localStorage.getItem("currentUser")).user_id;
  try {
    const response = await api.postData(
      "mouvement/apidelete",
      { "X-API-KEY": "ADMIN_SECRET_2026" },
      { id: id },
    );
    // const data = await response.text();
    console.log("response avnt:", response);

    if (response && response.success === true) {
      el("d-btntxt").textContent = "Actualisation...";
      await refreshData();
      await new Promise((r) => setTimeout(r, 500));

      el("delete-btn").disabled = false;
      el("d-btn-icon").textContent = "delete";
      el("d-btn-icon").classList.remove("spin");
      el("d-btntxt").textContent = " SUPPRIMER";
      el("section-details").classList.add("hidden");
      showToast("Mouvement supprimé avec succès !", "success");
    } else {
      el("delete-btn").disabled = false;
      el("d-btn-icon").textContent = "delete";
      el("d-btn-icon").classList.remove("spin");
      el("d-btntxt").textContent = " SUPPRIMER";
      showtoast(response.message || "Erreur lors de la supression", "error");
    }
  } catch (error) {
    console.error("Erreur lors de la validation du mouvement :", error);
    showtoast("Une erreur est survenue lors de la validation.", "error");
    el("delete-btn").disabled = false;
    el("d-btn-icon").textContent = "delete";
    el("d-btn-icon").classList.remove("spin");
    el("d-btntxt").textContent = " SUPPRIMER";
  }
}
async function deleteBon(id) {
  // if (!confirm("Êtes-vous sûr de vouloir supprimer ce mouvement ?")) return;

  el("b-delete-btn").disabled = true;
  el("b-d-btn-icon").textContent = "autorenew";
  el("b-d-btn-icon").classList.add("spin");
  el("b-d-btntxt").textContent = "Supression...";
  // let userid = JSON.parse(localStorage.getItem("currentUser")).user_id;
  try {
    const response = await api.postData(
      "mouvement/apideleteBon",
      { "X-API-KEY": "ADMIN_SECRET_2026" },
      { id: id },
    );
    // const data = await response.text();
    console.log("response avnt:", response);

    if (response && response.success === true) {
      el("b-d-btntxt").textContent = "Actualisation...";
      await refreshData();
      await new Promise((r) => setTimeout(r, 500));

      el("b-delete-btn").disabled = false;
      el("b-d-btn-icon").textContent = "delete";
      el("b-d-btn-icon").classList.remove("spin");
      el("b-d-btntxt").textContent = " SUPPRIMER";
      el("section-document-details").classList.add("hidden");
      el("section-documents").classList.remove("hidden");
      showToast("Mouvement supprimé avec succès !", "success");
    } else {
      el("b-delete-btn").disabled = false;
      el("b-d-btn-icon").textContent = "delete";
      el("b-d-btn-icon").classList.remove("spin");
      el("b-d-btntxt").textContent = " SUPPRIMER";
      showToast(response.message || "Erreur lors de la supression", "error");
    }
  } catch (error) {
    console.error("Erreur lors de la validation du mouvement :", error);
    showToast("Une erreur est survenue lors de la validation.", "error");
    el("b-delete-btn").disabled = false;
    el("b-d-btn-icon").textContent = "delete";
    el("b-d-btn-icon").classList.remove("spin");
    el("b-d-btntxt").textContent = " SUPPRIMER";
  }
}

// console.log("sesssion", sessionStorage);
// console.log(
//   "curentUser:",
//   JSON.parse(sessionStorage.getItem("currentUser")).user_id,
// );

async function validateMouvement(id) {
  el("b-validate-btn").disabled = true;
  el("b-btn-icon").textContent = "autorenew";
  el("b-btn-icon").classList.add("spin");
  el("b-btntxt").textContent = "Validation...";
  let userid = JSON.parse(sessionStorage.getItem("currentUser")).user_id;

  try {
    const response = await api.postData(
      "mouvement/apivalidate",
      { "X-API-KEY": "ADMIN_SECRET_2026" },
      { id: id, user_id: userid },
    );
    // const data = await response.text();
    // console.log("response avnt:", response);

    if (response && response.success === true) {
      el("b-btntxt").textContent = "Actualisation...";
      await refreshData();
      await new Promise((r) => setTimeout(r, 500));
      let mvn = getmovement(id);
      showdetails(mvn);
      // showtoast("Mouvement validé avec succès !", "success");
      // alert("Mouvement validé avec succès !");
      // console.log("rep apres", response);
      el("validate-btn").disabled = false;
      el("v-btn-icon").textContent = "check";
      el("v-btn-icon").classList.remove("spin");
      el("v-btntxt").textContent = " VALIDER";
    } else {
      el("validate-btn").disabled = false;
      el("v-btn-icon").textContent = "check";
      el("v-btn-icon").classList.remove("spin");
      el("v-btntxt").textContent = " VALIDER";
      showtoast(response.message || "Erreur lors de la validation", "error");
    }
  } catch (error) {
    console.error("Erreur lors de la validation du mouvement :", error);
    showtoast("Une erreur est survenue lors de la validation.", "error");
    el("validate-btn").disabled = false;
    el("v-btn-icon").textContent = "check";
    el("v-btn-icon").classList.remove("spin");
    el("v-btntxt").textContent = " VALIDER";
  }
}
async function validateBons(id) {
  el("b-validate-btn").disabled = true;
  el("b--v-btn-icon").textContent = "autorenew";
  el("b--v-btn-icon").classList.add("spin");
  el("b-btntxt").textContent = "Validation...";
  let userid = JSON.parse(sessionStorage.getItem("currentUser")).user_id;

  try {
    const response = await api.postData(
      "mouvement/apivalidateBon",
      { "X-API-KEY": "ADMIN_SECRET_2026" },
      { id: id, user_id: userid },
    );
    // const data = await response.text();
    console.log("response avnt:", response);

    if (response && response.success === true) {
      el("b-btntxt").textContent = "Actualisation...";
      await refreshData();
      await new Promise((r) => setTimeout(r, 500));

      // showtoast("Mouvement validé avec succès !", "success");
      // alert("Mouvement validé avec succès !");
      // console.log("rep apres", response);
      el("b-validate-btn").disabled = false;
      el("b--v-btn-icon").textContent = "check";
      el("b--v-btn-icon").classList.remove("spin");
      el("b-btntxt").textContent = " VALIDER";
    } else {
      el("b-validate-btn").disabled = false;
      el("b--v-btn-icon").textContent = "check";
      el("b--v-btn-icon").classList.remove("spin");
      el("b-btntxt").textContent = " VALIDER";
      showtoast(response.message || "Erreur lors de la validation", "error");
    }
  } catch (error) {
    console.error("Erreur lors de la validation du mouvement :", error);
    showtoast("Une erreur est survenue lors de la validation.", "error");
    el("b-validate-btn").disabled = false;
    el("b--v-btn-icon").textContent = "check";
    el("b--v-btn-icon").classList.remove("spin");
    el("b-btntxt").textContent = " VALIDER";
  }
}

document.getElementById("delete-btn").addEventListener("click", () => {
  const id = document.getElementById("mvn-id").textContent;
  deleteMouvement(id);
});

document.getElementById("validate-btn").addEventListener("click", () => {
  const id = document.getElementById("mvn-id").textContent;
  validateMouvement(id);
});
document.getElementById("b-validate-btn").addEventListener("click", () => {
  const id = document.getElementById("bon-id").textContent;
  validateBons(id);
});
document.getElementById("b-delete-btn").addEventListener("click", () => {
  const id = document.getElementById("bon-id").textContent;
  deleteBon(id);
});

// filtre avancé

const panel = document.getElementById("filters-panel");
const btnFilters = document.getElementById("open-filters");

btnFilters.addEventListener("click", () => {
  panel.classList.toggle("hidden");
});

// Fermer si clic en dehors
document.addEventListener("click", (e) => {
  if (!panel.contains(e.target) && !btnFilters.contains(e.target)) {
    panel.classList.add("hidden");
  }
});

document.getElementById("apply-filters").addEventListener("click", () => {
  const type = document.getElementById("filter-type").value;
  const statut = document.getElementById("filter-statut").value;
  const exactDate = document.getElementById("filter-date").value;

  let filters = {};

  if (type) filters.type = type;
  if (statut) filters.statut = statut;

  // Date précise
  if (exactDate) {
    const start = new Date(exactDate);
    start.setHours(0, 0, 0, 0);

    const end = new Date(exactDate);
    end.setHours(23, 59, 59, 999);

    filters.date_start = start.toISOString();
    filters.date_end = end.toISOString();
  }

  filterMouvements(filters);
  panel.classList.add("hidden");
});

document.getElementById("reset-filters").addEventListener("click", () => {
  document.getElementById("filter-type").value = "";
  document.getElementById("filter-statut").value = "";
  document.getElementById("filter-date").value = "";

  filterMouvements({});
});

const searchInput = el("search-input");
const BsearchInput = el("Bsearch-input");

searchInput.addEventListener("input", function () {
  const value = this.value.toLowerCase().trim();

  filteredMouvements = mouvements.filter((m) => {
    return (
      m.bon_document?.toLowerCase().includes(value) ||
      m.client?.toLowerCase().includes(value) ||
      m.type?.toLowerCase().includes(value) ||
      m.article_nom?.toLowerCase().includes(value) ||
      String(m.article_id).toLowerCase().includes(value) ||
      String(m.total)?.includes(value)
    );
  });

  currentPage = 1;
  renderPage(currentPage);
});

BsearchInput.addEventListener("input", function () {
  const value = this.value.toLowerCase().trim();

  filteredBons = bons.filter((b) => {
    // console.log(b);
    return (
      b.mouvements.article_nom?.toLowerCase().includes(value) ||
      String(b.mouvements.article_id).toLowerCase().includes(value) ||
      b.document?.toLowerCase().includes(value) ||
      b.createur?.toLowerCase().includes(value) ||
      b.type?.toLowerCase().includes(value) ||
      String(b.total)?.includes(value)
    );
  });

  currentPage = 1;
  renderBons(currentPage);
});
