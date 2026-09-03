let data = [];
let selectedIndex = -1;
let currentResults = [];

const searchInput = document.getElementById("search-input");
const suggestionsBox = document.getElementById("search-suggestions");
const suggestionsList = document.getElementById("suggestions-list");

/* =========================================================
   PANIER / DOCUMENT
========================================================= */

let cartData = JSON.parse(localStorage.getItem("cart")) || {
  header: {
    date: "",
    type: "",
    source: "",
    destination: "",
    document_id: null,
  },
  items: [],
};

/* =========================================================
   UTILITAIRES
========================================================= */

function el(id) {
  return document.getElementById(id);
}

function normalizeCartData() {
  if (!cartData || typeof cartData !== "object") {
    cartData = {
      header: {},
      items: [],
    };
  }

  if (!cartData.header || typeof cartData.header !== "object") {
    cartData.header = {};
  }

  if (!Array.isArray(cartData.items)) {
    cartData.items = [];
  }

  return cartData;
}

function normalizeItem(item) {
  item.qty = Number(item.qty) || 0;
  item.prix = Number(item.prix) || 0;
  item.ttl = item.prix * item.qty;

  return item;
}

function saveCart() {
  normalizeCartData();

  cartData.items.forEach(normalizeItem);

  localStorage.setItem("cart", JSON.stringify(cartData));

  renderCart();
}

/* =========================================================
   CHARGEMENT DES ARTICLES
========================================================= */

async function loadData() {
  try {
    const res = await fetch("/api/articles");

    if (!res.ok) {
      throw new Error("Impossible de charger les articles.");
    }

    const json = await res.json();

    data = json.data || json;

    if (!Array.isArray(data)) {
      data = [];
    }

    localStorage.setItem("articles", JSON.stringify(data));
  } catch (error) {
    console.log("API indisponible, chargement depuis localStorage...");

    data = JSON.parse(localStorage.getItem("articles")) || [];

    if (!Array.isArray(data)) {
      data = [];
    }
  }
}

/* =========================================================
   AFFICHAGE DU PANIER
========================================================= */

function renderCart() {
  normalizeCartData();

  const tbody = document.querySelector("tbody");

  if (!tbody) {
    return;
  }

  tbody.innerHTML = "";

  rendreheader();

  if (cartData.items.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="7" class="py-10">
          <div class="flex flex-col items-center justify-center text-gray-500">

            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-14 h-14 mb-3 text-gray-400"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"
              />
            </svg>

            <p class="text-lg font-semibold">
              Aucun article
            </p>

            <p class="text-sm text-gray-400">
              Ajoutez des articles au document
            </p>

          </div>
        </td>
      </tr>
    `;

    return;
  }

  cartData.items.forEach((item, index) => {
    normalizeItem(item);

    const tr = document.createElement("tr");

    tr.className = "hover:bg-primary/5 transition-colors group";

    const prixUnit = item.prix;
    const totalHT = item.ttl.toFixed(2);

    tr.innerHTML = `
      <td class="px-6 py-4">
        <div class="flex items-center gap-3">

          <div
            class="h-8 w-8 rounded bg-background-dark flex items-center justify-center"
          >
            <span
              class="material-symbols-outlined text-sm text-slate-400"
            >
              settings_input_hdmi
            </span>
          </div>

          <span class="text-sm font-medium">
            ${escapeHtml(item.nom)}
          </span>

        </div>
      </td>

      <td class="px-6 py-4 text-xs font-mono text-primary">
        REF-${item.id}
      </td>

      <td
        class="px-6 py-4"
        onclick="enableEdit(${index}, 'qty')"
      >
        <div class="flex items-center justify-center">

          <input
            id="qty-input-${index}"
            class="w-20 hidden no-spinner h-8 bg-background-dark border-border-dark rounded text-center text-sm focus:ring-primary"
            type="number"
            min="0"
            value="${item.qty}"
          />

          <span
            id="qty-value-${index}"
            class="text-sm font-medium text-center"
          >
            ${item.qty}
          </span>

        </div>
      </td>

      <td
        class="px-6 py-4"
        onclick="enableEdit(${index}, 'num')"
      >
        <div class="flex items-center justify-center">

          <input
            id="num-input-${index}"
            class="w-20 hidden no-spinner h-8 bg-background-dark border-border-dark rounded text-center text-sm focus:ring-primary"
            type="text"
            value="${escapeAttribute(item.num || "")}"
          />

          <span
            id="num-value-${index}"
            class="text-sm font-medium text-center"
          >
            ${escapeHtml(item.num || "-")}
          </span>

        </div>
      </td>

      <td class="px-6 py-4 text-right text-sm font-medium">
        ${prixUnit.toFixed(2)}
      </td>

      <td
        class="px-6 py-4 text-right text-sm font-bold text-slate-100"
      >
        ${totalHT}
      </td>

      <td class="px-6 py-4 text-right">

        <button
          type="button"
          onclick="removeFromCart(${index})"
          class="text-slate-500 hover:text-red-500 transition-colors"
        >
          <span class="material-symbols-outlined text-lg">
            delete
          </span>
        </button>

      </td>
    `;

    tbody.appendChild(tr);
  });
}

/* =========================================================
   PROTECTION HTML
========================================================= */

function escapeHtml(value) {
  return String(value ?? "")
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function escapeAttribute(value) {
  return escapeHtml(value);
}

/* =========================================================
   HEADER DU DOCUMENT
========================================================= */

function saveHeader() {
  const date = el("date")?.value || "";
  const type = el("type")?.value || "";
  const source = el("source")?.value || "";
  const destination = el("destination")?.value || "";

  cartData.header = {
    ...cartData.header,

    date,
    type,
    source,
    destination,
  };

  saveCart();
}

/* =========================================================
   AFFICHAGE DU HEADER
========================================================= */

function rendreheader() {
  normalizeCartData();

  const h = cartData.header;

  let source = null;
  let destination = null;

  if (h.source) {
    const entrepot = entrepots.find((i) => i.id === Number(h.source));

    if (entrepot) {
      source = entrepot.nom;
    }
  }

  if (h.destination) {
    const entrepot = entrepots.find((i) => i.id === Number(h.destination));

    if (entrepot) {
      destination = entrepot.nom;
    }
  }

  const nombreArticles = cartData.items.length;

  const quantiteTotale = cartData.items.reduce((total, item) => {
    return total + (Number(item.qty) || 0);
  }, 0);

  const totalHT = cartData.items.reduce((total, item) => {
    return total + (Number(item.prix) || 0) * (Number(item.qty) || 0);
  }, 0);

  if (el("v-date")) {
    el("v-date").textContent = h.date || "-";
  }

  if (el("v-type")) {
    el("v-type").textContent = h.type || "-";
  }

  if (el("v-source")) {
    el("v-source").textContent = source || "-";
  }

  if (el("v-destination")) {
    el("v-destination").textContent = destination || "-";
  }

  if (el("v-qty")) {
    el("v-qty").textContent = nombreArticles || "0";
  }

  if (el("v-tt")) {
    el("v-tt").textContent = quantiteTotale || "0";
  }

  if (el("v-utotal")) {
    el("v-utotal").textContent = totalHT.toFixed(2);
  }

  if (el("v-total")) {
    el("v-total").textContent = totalHT.toFixed(2);
  }
}

/* =========================================================
   BOUTON DÉMARRER DOCUMENT
========================================================= */

const startButton = el("startbtn");

if (startButton) {
  startButton.addEventListener("click", () => {
    saveHeader();

    el("new-doc-modal")?.classList.add("hidden");
  });
}

/* =========================================================
   AJOUT AU PANIER
========================================================= */

function addToCart(article) {
  normalizeCartData();

  const existingIndex = cartData.items.findIndex(
    (item) => Number(item.id) === Number(article.id),
  );

  let index;

  if (existingIndex !== -1) {
    const existing = cartData.items[existingIndex];

    existing.qty = (Number(existing.qty) || 0) + 1;

    normalizeItem(existing);

    index = existingIndex;
  } else {
    const newItem = {
      id: article.id,
      nom: article.nom,
      qty: 1,
      num: "",
      prix: Number(article.prix) || 0,
      ttl: Number(article.prix) || 0,
    };

    cartData.items.push(newItem);

    index = cartData.items.length - 1;
  }

  saveCart();

  setTimeout(() => {
    enableEdit(index, "qty");
  }, 50);
}

/* =========================================================
   SUPPRESSION ARTICLE
========================================================= */

function removeFromCart(index) {
  if (index < 0 || index >= cartData.items.length) {
    return;
  }

  cartData.items.splice(index, 1);

  saveCart();
}

/* =========================================================
   ÉDITION ARTICLE
========================================================= */

function enableEdit(index, type) {
  normalizeCartData();

  const input = document.getElementById(`${type}-input-${index}`);

  const span = document.getElementById(`${type}-value-${index}`);

  if (!input || !span) {
    return;
  }

  input.classList.remove("hidden");
  span.classList.add("hidden");

  input.focus();

  const value = input.value;

  input.value = "";
  input.value = value;

  /* -----------------------------------------
     ENTRÉE CLAVIER
  ----------------------------------------- */

  input.onkeydown = (event) => {
    if (event.key !== "Enter") {
      return;
    }

    event.preventDefault();

    const item = cartData.items[index];

    if (!item) {
      return;
    }

    if (type === "qty") {
      const quantity = parseInt(input.value, 10);

      item.qty = Number.isFinite(quantity) ? Math.max(0, quantity) : 0;

      normalizeItem(item);

      saveCart();

      setTimeout(() => {
        enableEdit(index, "num");
      }, 50);
    } else {
      item.num = input.value.trim();

      saveCart();
    }
  };

  /* -----------------------------------------
     PERTE DU FOCUS
  ----------------------------------------- */

  input.onblur = () => {
    const item = cartData.items[index];

    if (!item) {
      return;
    }

    if (type === "qty") {
      const quantity = parseInt(input.value, 10);

      item.qty = Number.isFinite(quantity) ? Math.max(0, quantity) : 0;

      normalizeItem(item);
    } else {
      item.num = input.value.trim();
    }

    saveCart();
  };
}

/* =========================================================
   RECHERCHE ARTICLES
========================================================= */

function setupSearch() {
  if (!searchInput) {
    return;
  }

  searchInput.addEventListener("input", function () {
    const query = this.value.toLowerCase().trim();

    selectedIndex = -1;

    if (query.length === 0) {
      suggestionsBox.classList.add("hidden");

      return;
    }

    currentResults = data
      .filter((article) => {
        const nom = String(article.nom || "").toLowerCase();

        const id = String(article.id || "");

        return nom.includes(query) || id.includes(query);
      })
      .slice(0, 5);

    renderSuggestions(currentResults);
  });

  searchInput.addEventListener("keydown", function (event) {
    const items = suggestionsList.querySelectorAll("button");

    if (event.key === "ArrowDown") {
      event.preventDefault();

      if (items.length === 0) {
        return;
      }

      selectedIndex++;

      if (selectedIndex >= items.length) {
        selectedIndex = 0;
      }

      updateSelection(items);
    } else if (event.key === "ArrowUp") {
      event.preventDefault();

      if (items.length === 0) {
        return;
      }

      selectedIndex--;

      if (selectedIndex < 0) {
        selectedIndex = items.length - 1;
      }

      updateSelection(items);
    } else if (event.key === "Enter") {
      if (selectedIndex >= 0 && currentResults[selectedIndex]) {
        event.preventDefault();

        addToCart(currentResults[selectedIndex]);

        suggestionsBox.classList.add("hidden");

        this.value = "";

        selectedIndex = -1;
      }
    }
  });
}

/* =========================================================
   SÉLECTION RECHERCHE
========================================================= */

function updateSelection(items) {
  items.forEach((item, index) => {
    if (index === selectedIndex) {
      item.classList.add("bg-primary/20");

      item.scrollIntoView({
        block: "nearest",
      });
    } else {
      item.classList.remove("bg-primary/20");
    }
  });
}

/* =========================================================
   AFFICHAGE SUGGESTIONS
========================================================= */

function renderSuggestions(results) {
  suggestionsList.innerHTML = "";

  if (results.length === 0) {
    suggestionsBox.classList.add("hidden");

    return;
  }

  results.forEach((article, index) => {
    const item = document.createElement("button");

    item.type = "button";

    item.className =
      "w-full flex items-center gap-4 p-3 hover:bg-primary/10 text-left border-b border-border-dark/30";

    item.innerHTML = `
      <div class="flex-1">

        <p class="text-sm font-semibold">
          ${escapeHtml(article.nom)}
        </p>

        <p class="text-[10px] font-mono text-slate-500">
          REF-${escapeHtml(article.id)}
        </p>

      </div>

      <span
        class="material-symbols-outlined text-primary"
      >
        add_circle
      </span>
    `;

    item.addEventListener("click", () => {
      addToCart(article);

      suggestionsBox.classList.add("hidden");

      searchInput.value = "";

      selectedIndex = -1;
    });

    suggestionsList.appendChild(item);
  });

  suggestionsBox.classList.remove("hidden");
}

/* =========================================================
   SAUVEGARDE DOCUMENT
========================================================= */

const saveButton = el("save-btn");

if (saveButton) {
  saveButton.addEventListener("click", sauvegarder);
}

async function sauvegarder() {
  normalizeCartData();

  if (cartData.items.length === 0) {
    console.warn("Impossible de sauvegarder un document vide.");

    return;
  }

  try {
    /* -----------------------------------------
       NORMALISATION DES ARTICLES
    ----------------------------------------- */

    cartData.items.forEach(normalizeItem);

    /* -----------------------------------------
       TOTAL
    ----------------------------------------- */

    const total = cartData.items.reduce((acc, item) => {
      return acc + Number(item.prix) * Number(item.qty);
    }, 0);

    /* -----------------------------------------
       SOURCE
    ----------------------------------------- */

    const source = Number(cartData.header.source) || null;

    /* -----------------------------------------
       DESTINATION
    ----------------------------------------- */

    const destinationValue = Number(cartData.header.destination);

    const destination =
      Number.isFinite(destinationValue) && destinationValue !== 0
        ? destinationValue
        : null;

    /* -----------------------------------------
       PAYLOAD
    ----------------------------------------- */

    const payload = {
      date: cartData.header.date || new Date().toISOString().slice(0, 10),

      type: cartData.header.type || "standard",

      source,

      destination,

      total: Number(total.toFixed(2)),

      comment: el("comment")?.value?.trim() || "",

      id: cartData.header.document_id || null,

      items: cartData.items.map((item) => ({
        id: item.id,
        nom: item.nom,
        qty: Number(item.qty) || 0,
        num: item.num || "",
        prix: Number(item.prix) || 0,
        ttl: Number(item.prix) * Number(item.qty),
      })),
    };

    console.log("Document envoyé :", payload);

    /* -----------------------------------------
       REQUÊTE
    ----------------------------------------- */

    const req = await fetch("/document", {
      method: "POST",

      headers: {
        "Content-Type": "application/json",
      },

      body: JSON.stringify(payload),
    });

    /* -----------------------------------------
       VÉRIFICATION
    ----------------------------------------- */

    if (!req.ok) {
      let message = "Erreur serveur.";

      try {
        const errorData = await req.json();

        message = errorData.message || message;
      } catch {
        // réponse non JSON
      }

      throw new Error(message);
    }

    /* -----------------------------------------
       RÉPONSE
    ----------------------------------------- */

    const contentType = req.headers.get("content-type") || "";

    let responseData;

    if (contentType.includes("application/json")) {
      responseData = await req.json();
    } else {
      responseData = await req.text();
    }

    console.log("Document sauvegardé :", responseData);

    /* -----------------------------------------
       NETTOYAGE
    ----------------------------------------- */

    localStorage.removeItem("cart");

    cartData = {
      header: {
        date: "",
        type: "",
        source: "",
        destination: "",
        document_id: null,
      },
      items: [],
    };

    /* -----------------------------------------
       RESET FORMULAIRE
    ----------------------------------------- */

    if (el("comment")) {
      el("comment").value = "";
    }

    renderCart();

    await init();
  } catch (error) {
    console.error("Erreur sauvegarde :", error);

    alert(error.message || "Une erreur est survenue lors de la sauvegarde.");
  }
}

/* =========================================================
   BROUILLONS
========================================================= */

let drafts = [];

const draftButton = document.getElementById("draftButton");

const draftList = document.getElementById("draftList");

const draftItems = document.getElementById("draftItems");

const draftCount = document.getElementById("draftCount");

const closeDrafts = document.getElementById("closeDrafts");

const draftButtonWrapper = document.getElementById("draftButtonWrapper");

/* =========================================================
   CHARGER BROUILLONS
========================================================= */

async function fetchDrafts() {
  const req = await fetch("/document/brouillons");

  if (!req.ok) {
    throw new Error("Impossible de charger les brouillons.");
  }

  const response = await req.json();

  /*
   * Si l'API retourne :
   *
   * [
   *   ...
   * ]
   *
   * on utilise directement.
   *
   * Si elle retourne :
   *
   * { data: [...] }
   *
   * on utilise data.
   */

  return Array.isArray(response) ? response : response.data || [];
}

/* =========================================================
   INITIALISATION BROUILLONS
========================================================= */

async function init() {
  try {
    drafts = await fetchDrafts();

    if (!Array.isArray(drafts)) {
      drafts = [];
    }

    /* compteur */

    if (draftCount) {
      draftCount.textContent = drafts.length;
    }

    /* bouton */

    if (draftButtonWrapper) {
      if (drafts.length > 0) {
        draftButtonWrapper.classList.remove("hidden");
      } else {
        draftButtonWrapper.classList.add("hidden");
      }
    }
  } catch (error) {
    console.error("Erreur chargement brouillons :", error);

    drafts = [];

    if (draftCount) {
      draftCount.textContent = "0";
    }
  }
}

/* =========================================================
   AFFICHER BROUILLONS
========================================================= */

if (draftButton) {
  draftButton.addEventListener("click", () => {
    if (!draftItems) {
      return;
    }

    draftItems.innerHTML = "";

    if (drafts.length === 0) {
      draftItems.innerHTML = `
          <li class="p-3 text-sm text-gray-400">
            Aucun brouillon disponible.
          </li>
        `;
    } else {
      drafts.forEach((doc) => {
        const li = document.createElement("li");

        li.className =
          "p-3 bg-gray-800 hover:bg-gray-700 rounded-lg flex justify-between font-medium cursor-pointer transition-colors";

        li.innerHTML = `
            <span>
              ${escapeHtml(doc.numero)}
            </span>

            <span>
              ${Number(doc.total || 0).toFixed(2)}$
            </span>
          `;

        li.addEventListener("click", () => {
          openDraft(doc);

          draftList?.classList.add("hidden");
        });

        draftItems.appendChild(li);
      });
    }

    draftList?.classList.remove("hidden");
  });
}

/* =========================================================
   OUVRIR UN BROUILLON
========================================================= */

function openDraft(draft) {
  if (!draft) {
    return;
  }

  normalizeCartData();

  /* -----------------------------------------
     ID DOCUMENT
  ----------------------------------------- */

  cartData.header.document_id = draft.id;

  /* -----------------------------------------
     HEADER DU BROUILLON
  ----------------------------------------- */

  cartData.header = {
    ...cartData.header,

    date: draft.date || cartData.header.date || "",

    type: draft.type || cartData.header.type || "",

    source: draft.source ?? cartData.header.source ?? "",

    destination: draft.destination ?? cartData.header.destination ?? "",

    document_id: draft.id,
  };

  /* -----------------------------------------
     ARTICLES
  ----------------------------------------- */

  cartData.items = Array.isArray(draft.items)
    ? draft.items.map((item) => {
        const normalizedItem = {
          id: item.id,

          nom: item.article || item.nom || "",

          qty: Number(item.quantite ?? item.qty ?? 0),

          prix: Number(item.prix ?? 0),

          num: item.page || item.num || "",
        };

        return normalizeItem(normalizedItem);
      })
    : [];

  /* -----------------------------------------
     SAUVEGARDE LOCAL
  ----------------------------------------- */

  localStorage.setItem("cart", JSON.stringify(cartData));

  /* -----------------------------------------
     AFFICHAGE
  ----------------------------------------- */

  renderCart();

  /* -----------------------------------------
     BOUTON AJOUT ARTICLE
  ----------------------------------------- */

  const addButton = el("add-doc-btn");

  if (addButton) {
    addButton.classList.remove("hidden");

    /*
     * On supprime l'ancien listener
     * avant d'en mettre un nouveau.
     */

    addButton.onclick = startNewDocumentItems;
  }
}

/* =========================================================
   AJOUTER DES ARTICLES À UN BROUILLON
========================================================= */

function startNewDocumentItems() {
  const documentId = cartData.header.document_id;

  /*
   * On conserve l'ID du document
   * si nécessaire.
   */

  cartData = {
    header: {
      document_id: documentId || null,

      date: "",
      type: "",
      source: "",
      destination: "",
    },

    items: [],
  };

  localStorage.setItem("cart", JSON.stringify(cartData));

  renderCart();

  const addButton = el("add-doc-btn");

  if (addButton) {
    addButton.classList.add("hidden");
  }

  searchInput?.focus();
}

/* =========================================================
   FERMER BROUILLONS
========================================================= */

if (closeDrafts) {
  closeDrafts.addEventListener("click", () => {
    draftList?.classList.add("hidden");
  });
}

/* =========================================================
   DOM READY
========================================================= */

document.addEventListener("DOMContentLoaded", async () => {
  normalizeCartData();

  await loadData();

  renderCart();

  setupSearch();

  await init();

  searchInput?.focus();
});
