let data = [];
let selectedIndex = -1;
let currentResults = [];
let cart = JSON.parse(localStorage.getItem("cart")) || [];

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

  cart.forEach((item, index) => {
    const tr = document.createElement("tr");
    tr.className = "hover:bg-primary/5 transition-colors group";

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
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCart();
  searchInput.focus();
}

function addToCart(article) {
  const existing = cart.find((i) => i.id === article.id);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({
      id: article.id,
      nom: article.nom,
      qty: 1,
      num: "", // n° reçu vide par défaut
      prix: article.prix || 0,
    });
  }
  saveCart();
  // Focus auto sur la quantité du dernier article ajouté
  setTimeout(() => enableEdit(cart.length - 1, "qty"), 50);
}

function removeFromCart(index) {
  cart.splice(index, 1);
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
