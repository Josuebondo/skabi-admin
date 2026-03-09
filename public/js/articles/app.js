document.addEventListener("DOMContentLoaded", () => {
  const ARTICLES_PER_PAGE = 25; // articles par page
  let currentPage = 1;
  let articles = [];

  function showLoading() {
    const container = document.getElementById("grid-container");
    container.innerHTML = "";

    for (let i = 0; i < 8; i++) {
      container.innerHTML += `
      <div class="bg-white dark:bg-card-dark border border-slate-200 dark:border-slate-800 p-3 rounded-lg flex flex-col gap-2 animate-pulse">
        
        <div class="flex flex-col gap-2">
          <div class="h-2 w-20 bg-slate-200 dark:bg-slate-700 rounded"></div>
          <div class="h-3 w-32 bg-slate-200 dark:bg-slate-700 rounded"></div>
        </div>

        <div class="flex flex-col gap-2 py-2 border-y border-slate-100 dark:border-slate-800/50">
          <div class="flex justify-between">
            <div class="h-2 w-12 bg-slate-200 dark:bg-slate-700 rounded"></div>
            <div class="h-2 w-8 bg-slate-200 dark:bg-slate-700 rounded"></div>
          </div>
          <div class="flex justify-between">
            <div class="h-2 w-12 bg-slate-200 dark:bg-slate-700 rounded"></div>
            <div class="h-2 w-8 bg-slate-200 dark:bg-slate-700 rounded"></div>
          </div>
        </div>

        <div class="flex justify-between items-center">
          <div class="h-3 w-16 bg-slate-200 dark:bg-slate-700 rounded"></div>
          <div class="h-5 w-5 bg-slate-200 dark:bg-slate-700 rounded"></div>
        </div>

      </div>
    `;
    }
  }
  async function load() {
    showLoading();
    let res = await fetch("api/articles");
    let data = await res.json();

    articles = data.data; // tableau d'articles
    localStorage.removeItem("articles");
    localStorage.setItem("articles", JSON.stringify(data.data));

    renderPage(currentPage);
  }

  function renderPage(page) {
    const container = document.getElementById("grid-container");
    container.innerHTML = "";

    const info = document.getElementById("pagination-info");
    const start = (currentPage - 1) * ARTICLES_PER_PAGE + 1;
    const end = Math.min(currentPage * ARTICLES_PER_PAGE, articles.length);
    info.textContent = `Articles: ${start}-${end} / ${articles.length}`;

    const pageArticles = articles.slice(start, end);

    pageArticles.forEach((d) => {
      const item = document.createElement("div");
      item.className =
        "bg-white dark:bg-card-dark border border-slate-200 dark:border-slate-800 p-3 rounded-lg flex flex-col gap-2 hover:border-primary/50 transition-colors group cursor-pointer";

      item.innerHTML = `
        <div class="flex flex-col">
          <span class="text-[10px] font-mono text-primary/70 dark:text-primary/80 leading-none mb-1 uppercase">REF-${d.id}-DX</span>
          <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate group-hover:text-primary transition-colors">${d.nom}</h3>
        </div>

        <div class="flex flex-col gap-1 py-2 border-y border-slate-100 dark:border-slate-800/50">
          ${d.stocks
            .map(
              (
                s,
              ) => `<div class="flex justify-between items-center text-[10px]">
                      <span class="text-slate-500">Entrepot ${s.entrepot}: </span>
                      <span class="font-bold">${s.stock}</span>
                    </div>`,
            )
            .join("")}       
        </div>

        <div class="flex items-center justify-between">
          <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-tighter">
            Total: ${d.stocks.reduce((acc, s) => acc + s.stock, 0)}
          </span>
          <button class="text-slate-400 group-hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-sm">visibility</span>
          </button>
        </div>
      `;

      container.appendChild(item);
    });

    renderPagination();
  }

  function renderPagination() {
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";

    const totalPages = Math.ceil(articles.length / ARTICLES_PER_PAGE);

    // bouton précédent
    const prevBtn = document.createElement("button");
    prevBtn.textContent = "Précédent";
    prevBtn.disabled = currentPage === 1;
    prevBtn.className = "px-3 py-1 border rounded mr-2";
    prevBtn.onclick = () => {
      currentPage--;
      renderPage(currentPage);
    };
    pagination.appendChild(prevBtn);

    // pages visibles autour de la page courante
    const maxButtons = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxButtons - 1);

    // ajuster si on est proche de la fin
    startPage = Math.max(1, endPage - maxButtons + 1);

    for (let i = startPage; i <= endPage; i++) {
      const btn = document.createElement("button");
      btn.textContent = i;
      btn.className = `px-3 py-1 border rounded mr-2 ${i === currentPage ? "bg-blue-500 text-white" : ""}`;
      btn.onclick = () => {
        currentPage = i;
        renderPage(currentPage);
      };
      pagination.appendChild(btn);
    }

    // bouton suivant
    const nextBtn = document.createElement("button");
    nextBtn.textContent = "Suivant";
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.className = "px-3 py-1 border rounded";
    nextBtn.onclick = () => {
      currentPage++;
      renderPage(currentPage);
    };
    pagination.appendChild(nextBtn);
  }

  load();
});
