let data = [];
let selectedIndex = -1;
let currentResults = [];
// localStorage.removeItem("cart");

let cartData = JSON.parse(localStorage.getItem("cart")) || {
  header: {
    date: "",
    type: "",
    source: "",
    destination: "",
  },
  items: [],
};
// localStorage.push();
// console.log(cartData);
let cart = cartData.items;
let cartHeader = cartData.header;
// console.log(entrepots);

const searchInput = document.getElementById("search-input");
const suggestionsBox = document.getElementById("search-suggestions");
const suggestionsList = document.getElementById("suggestions-list");
// --- CHARGEMENT DES DONNÉES ---
async function loadData() {
  try {
    const res = await fetch("/api/articles");
    const json = await res.json();
    data = json.data || json;
    localStorage.setItem("articles", JSON.stringify(data));
  } catch (error) {
    console.log("API indisponible, chargement localStorage");
    data = JSON.parse(localStorage.getItem("articles")) || [];
  }
}

// --- LOGIQUE DU PANIER (AFFICHAGE) ---
function renderCart() {
  const tbody = document.querySelector("tbody");
  tbody.innerHTML = ""; // On vide le tableau avant de reconstruire
  rendreheader();
  if (cartData.items.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="7" class="py-10">
          <div class="flex flex-col items-center justify-center text-gray-500">

            <svg xmlns="http://www.w3.org/2000/svg"
              class="w-14 h-14 mb-3 text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/>
            </svg>

            <p class="text-lg font-semibold">Aucun article</p>
            <p class="text-sm text-gray-400">Ajoutez des articles au document</p>

          </div>
        </td>
      </tr>
    `;
  }
  cartData.items.forEach((item, index) => {
    const tr = document.createElement("tr");
    tr.className = "hover:bg-primary/5 transition-colors group";
    console.log("redu");
    // Calcul du total (exemple si prix_unit existe dans vos données)
    const prixUnit = Number(item.prix) || 0;
    const totalHT = (prixUnit * (Number(item.qty) || 0)).toFixed(2);

    tr.innerHTML = `
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded bg-background-dark flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-slate-400">settings_input_hdmi</span>
                    </div>
                    <span class="text-sm font-medium">${item.nom}</span>
                </div>
            </td>
            <td class="px-6 py-4 text-xs font-mono text-primary">REF-${item.id}</td>
            <td class="px-6 py-4" onclick="enableEdit(${index}, 'qty')">
                <div class="flex items-center justify-center">
                    <input id="qty-input-${index}" class="w-20 hidden no-spinner h-8 bg-background-dark border-border-dark rounded text-center text-sm focus:ring-primary" type="number" value="${item.qty}" />
                    <span id="qty-value-${index}" class="text-sm font-medium text-center">${item.qty}</span>
                </div>
            </td>
            <td class="px-6 py-4" onclick="enableEdit(${index}, 'num')">
                <div class="flex items-center justify-center">
                    <input id="num-input-${index}" class="w-20 hidden no-spinner h-8 bg-background-dark border-border-dark rounded text-center text-sm focus:ring-primary" type="text" value="${item.num || ""}" />
                    <span id="num-value-${index}" class="text-sm font-medium text-center">${item.num || "-"}</span>
                </div>
            </td>
            <td class="px-6 py-4 text-right text-sm font-medium">${prixUnit.toFixed(2)}</td>
            <td class="px-6 py-4 text-right text-sm font-bold text-slate-100">${totalHT}</td>
            <td class="px-6 py-4 text-right">
                <button onclick="removeFromCart(${index})" class="text-slate-500 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined text-lg">delete</span>
                </button>
            </td>
        `;
    tbody.appendChild(tr);
  });
}

// --- FONCTIONS UTILITAIRES ---

function saveCart() {
  localStorage.setItem("cart", JSON.stringify(cartData));
  renderCart();
  searchInput.focus();
}
function el(id) {
  return document.getElementById(id);
}
function saveHeader() {
  let date = el("date").value;
  let type = el("type").value;
  let source = el("source").value;
  let destination = el("destination").value;

  cartData.header = {
    date: date,
    type: type,
    source: source,
    destination: destination,
  };

  saveCart();
}
el("startbtn").addEventListener("click", () => {
  saveHeader();
  el("new-doc-modal").classList.add("hidden");
});
function rendreheader() {
  let h = cartData.header;
  let source = null;
  let destination = null;
  if (h.source) {
    source = entrepots.find((i) => i.id === Number(h.source)).nom;
  }
  if (h.destination) {
    destination = entrepots.find((i) => i.id === Number(h.destination)).nom;
  }
  let qty = cartData.items.length;
  const sommeB = cartData.items.reduce((accumulateur, objetCourant) => {
    return accumulateur + objetCourant.qty;
  }, 0); // Important : '0' est la valeur de départ
  let ttu = cartData.items.reduce((accumulateur, objetCourant) => {
    // console.log(Number(objetCourant.prix));
    return accumulateur + Number(objetCourant.prix);
  }, 0); // Important : '0' est la valeur de départ
  let ttl = cartData.items.reduce((accumulateur, objetCourant) => {
    // console.log(cartData.items);
    return accumulateur + Number(objetCourant.ttl);
  }, 0); // Important : '0' est la valeur de départ
  // console.log(source);
  el("v-date").textContent = h.date || "-";
  el("v-type").textContent = h.type || "-";
  el("v-source").textContent = source || "-";
  el("v-destination").textContent = destination || "-";
  el("v-qty").textContent = qty || "0";
  el("v-tt").textContent = sommeB || "0";
  el("v-utotal").textContent = ttu.toFixed(2) || "0";
  el("v-total").textContent = ttl.toFixed(2) || "0";
}
function addToCart(article) {
  const existing = cartData.items.find((i) => i.id === article.id);
  let index = null;
  if (existing) {
    existing.qty += 1;
    index = cartData.items.findIndex((i) => i.id === existing.id);
    // console.log(index);
  } else {
    cartData.items.push({
      id: article.id,
      nom: article.nom,
      qty: 1,
      num: "",
      prix: Number(article.prix) || 0,
      ttl: Number(article.prix) * 1 || 0,
    });
    index = cartData.items.length - 1;
  }

  saveCart();
  setTimeout(() => enableEdit(index, "qty"), 50);
}
function removeFromCart(index) {
  cartData.items.splice(index, 1);
  saveCart();
}

// Gère l'édition et le curseur à la fin
function enableEdit(index, type) {
  const input = document.getElementById(`${type}-input-${index}`);
  const span = document.getElementById(`${type}-value-${index}`);

  if (!input) return;

  input.classList.remove("hidden");
  span.classList.add("hidden");

  // Placer le curseur à la fin
  input.focus();
  const val = input.value;
  input.value = "";
  input.value = val;

  input.onkeydown = (e) => {
    if (e.key === "Enter") {
      if (type === "qty") {
        cart[index].qty = parseInt(input.value) || 0;
        cart[index].ttl = cart[index].prix * parseInt(input.value) || 0;
        saveCart();
        enableEdit(index, "num"); // Passe au champ n° reçu automatiquement
      } else {
        cart[index].num = input.value;
        saveCart();
      }
    }
  };

  // Si on clique ailleurs, on enregistre
  input.onblur = () => {
    if (type === "qty") cart[index].qty = parseInt(input.value) || 0;
    else cart[index].num = input.value;
    saveCart();
  };
}

// --- EVENEMENTS DOM ---
document.addEventListener("DOMContentLoaded", async () => {
  await loadData();
  renderCart();
  // rendreheader();
  searchInput.addEventListener("input", function () {
    const query = this.value.toLowerCase().trim();
    if (query.length === 0) {
      suggestionsBox.classList.add("hidden");
      return;
    }

    currentResults = data
      .filter(
        (a) =>
          a.nom.toLowerCase().includes(query) || String(a.id).includes(query),
      )
      .slice(0, 5);

    renderSuggestions(currentResults);
  });
  searchInput.addEventListener("keydown", function (e) {
    const items = suggestionsList.querySelectorAll("button");

    if (e.key === "ArrowDown") {
      e.preventDefault(); // Empêche le curseur de bouger dans l'input
      selectedIndex++;
      if (selectedIndex >= items.length) selectedIndex = 0;
      updateSelection(items);
    } else if (e.key === "ArrowUp") {
      e.preventDefault(); // Empêche le curseur de bouger dans l'input
      selectedIndex--;
      if (selectedIndex < 0) selectedIndex = items.length - 1;
      updateSelection(items);
    } else if (e.key === "Enter") {
      if (selectedIndex >= 0 && currentResults[selectedIndex]) {
        e.preventDefault(); // Bloque la soumission du formulaire

        // On ajoute l'article sélectionné au panier
        addToCart(currentResults[selectedIndex]);

        // On vide et on cache les suggestions
        suggestionsBox.classList.add("hidden");
        this.value = ""; // "this" fait référence à searchInput
        selectedIndex = -1;
      }
    }
  });

  // N'oubliez pas la fonction de mise en surbrillance visuelle
  function updateSelection(items) {
    items.forEach((item, index) => {
      if (index === selectedIndex) {
        item.classList.add("bg-primary/20"); // Style de sélection
        item.scrollIntoView({ block: "nearest" }); // Garde l'élément visible
      } else {
        item.classList.remove("bg-primary/20");
      }
    });
  }
  function renderSuggestions(results) {
    suggestionsList.innerHTML = "";
    if (results.length === 0) {
      suggestionsBox.classList.add("hidden");
      return;
    }

    results.forEach((article, index) => {
      const item = document.createElement("button");
      item.className =
        "w-full flex items-center gap-4 p-3 hover:bg-primary/10 text-left border-b border-border-dark/30";
      item.innerHTML = `
                <div class="flex-1">
                    <p class="text-sm font-semibold">${article.nom}</p>
                    <p class="text-[10px] font-mono text-slate-500">REF-${article.id}</p>
                </div>
                <span class="material-symbols-outlined text-primary">add_circle</span>
            `;
      item.addEventListener("click", () => {
        addToCart(article);
        suggestionsBox.classList.add("hidden");
        searchInput.value = "";
      });
      suggestionsList.appendChild(item);
    });
    suggestionsBox.classList.remove("hidden");
  }
});

// saving panier

el("save-btn").addEventListener("click", () => {
  sauvegarder();
});
async function sauvegarder() {
  try {
    // Calcul du total général
    let total = cartData.items.reduce((acc, item) => {
      return acc + Number(item.prix) * Number(item.qty);
    }, 0);
    let des = null;
    if (Number(cartData.header.destination) !== 0) {
      des = Number(cartData.header.destination);
    }
    let data = {
      date: cartData.header.date ?? new Date().toISOString().slice(0, 10), // par défaut aujourd'hui si date absente
      type: cartData.header.type ?? "standard", // type par défaut
      source: Number(cartData.header.source) || null, // si source absent, null
      destination: des ?? null,
      total: Number(total.toFixed(2)) || 0, // s'assure que c'est toujours un nombre
      comment: el("comment").value ?? "",
      id: cartData.header.document_id ?? null, //
      items: cartData.items ?? [], // s'assure que c'est toujours un tableau
    };
    console.log(data);
    let req = await fetch("/document", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    // if (!req.ok) throw new Error("Erreur serveur");

    let res = await req.text();
    // console.log(res);
    // return;
    // vider le panier après succès
    localStorage.removeItem("cart");
    cartData = { header: {}, items: [] };
    init();
    renderCart();
  } catch (error) {
    console.error("Erreur sauvegarde :", error);
  }
}

async function fetchDrafts() {
  const req = await fetch("/document/brouillons");
  return await req.json();
}

const draftButton = document.getElementById("draftButton");
const draftList = document.getElementById("draftList");
const draftItems = document.getElementById("draftItems");
const draftCount = document.getElementById("draftCount");
const closeDrafts = document.getElementById("closeDrafts");
const draftButtonWrapper = document.getElementById("draftButtonWrapper");

let drafts = [];

async function init() {
  try {
    drafts = await fetchDrafts();

    // mettre à jour le compteur
    draftCount.textContent = drafts.length;

    // cacher le bouton si aucun brouillon
    if (drafts.length !== 0) {
      draftButtonWrapper.classList.remove("hidden");
    }

    // draftButtonWrapper.classList.remove("hidden");
  } catch (error) {
    console.error("Erreur chargement brouillons :", error);
  }
}

init();

draftButton.addEventListener("click", () => {
  draftItems.innerHTML = "";

  drafts.forEach((doc) => {
    const li = document.createElement("li");

    li.className =
      "p-3 bg-gray-800 hover:bg-gray-700 rounded-lg flex justify-between font-medium cursor-pointer transition-colors";

    li.innerHTML = `
      <span>${doc.numero}</span>
      <span>${Number(doc.total).toFixed(2)}$</span>
    `;

    li.addEventListener("click", () => {
      openDraft(doc);
      draftList.classList.add("hidden");
    });

    draftItems.appendChild(li);
  });

  draftList.classList.remove("hidden");
});
function openDraft(draft) {
  cartData.header.document_id = draft.id;

  cartData.items = draft.items.map((item) => ({
    id: item.id,
    nom: item.article,
    qty: Number(item.quantite),
    prix: Number(item.prix),
    num: item.page,
  }));
  renderCart();
  el("add-doc-btn").addEventListener("click", () => additemes());
  el("add-doc-btn").classList.remove("hidden");
  // console.log("Draft chargé :", cartData);
}
function additemes(id) {
  cartData = { header: {}, items: [] };
  cartData.header.document_id = id;
  renderCart();
  el("add-doc-btn").classList.add("hidden");
}
closeDrafts.addEventListener("click", () => {
  draftList.classList.add("hidden");
});
