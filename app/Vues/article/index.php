<!DOCTYPE html>
<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Inventaire des Articles - Vue Compacte</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#0a0f1a",
                        "card-dark": "#111827",
                        "modal-dark": "#0f172a",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        body {
            font-family: 'Manrope', sans-serif;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #135bec44;
            border-radius: 10px;
        }
        .grid-compact {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.75rem;
        }
        @media (min-width: 640px) { .grid-compact { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (min-width: 1024px) { .grid-compact { grid-template-columns: repeat(4, minmax(0, 1fr)); } }
        @media (min-width: 1280px) { .grid-compact { grid-template-columns: repeat(6, minmax(0, 1fr)); } }
        @media (min-width: 1536px) { .grid-compact { grid-template-columns: repeat(8, minmax(0, 1fr)); } }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen">
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
        <header class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/80 px-4 py-3 sticky top-0 z-40 backdrop-blur-md">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 text-primary">
                    <div class="size-7 bg-primary/10 rounded-md flex items-center justify-center">
                        <span class="material-symbols-outlined text-lg text-primary">inventory_2</span>
                    </div>
                    <h2 class="text-slate-900 dark:text-white text-lg font-bold tracking-tight">StockPro</h2>
                </div>
                <nav class="hidden lg:flex items-center gap-5">
                    <a class="text-slate-500 dark:text-slate-400 hover:text-primary text-xs font-bold uppercase tracking-wider" href="/documents">Documents</a>
                    <a class="text-primary text-xs font-bold border-b-2 border-primary pb-0.5 uppercase tracking-wider" href="/articles">Articles</a>
                    <a class="text-slate-500 dark:text-slate-400 hover:text-primary text-xs font-bold uppercase tracking-wider" href="/Mouvements">Mouvements</a>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <button class="size-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">notifications</span>
                </button>
                <div class="size-8 rounded-full border border-primary/20 p-0.5">
                    <div class="w-full h-full rounded-full bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDyhf6isQp6eGtis2tv4chSW3081jrv_6fBc9VQoQSyquWjEGKg3MiHrTYBaGO_ixgnAaw7aVHsCQ_mW-ojfCys0Yratze-bBYrTR-gb0VLA4oqueVTbFWBK_O4bSDOyCh_mvacIg4XxnqzISdL3FElxbgu29yHEQvzo2tJVnoLTvZfnAaiGMwjekdtfh9Yy-pqweqwASyhYOcN25fq4AowrJkhi9s4RTTrihvLz-RR3pq_G1XZhdZE1xhDioXLV3WkNRIghoS68lg')"></div>
                </div>
            </div>
        </header>
        <main class="flex-1 flex flex-col w-full px-4 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Inventaire par Article</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-xs">Vue ultra-dense du stock multi-entrepôts</p>
                </div>
                <div class="flex items-center gap-2">
                    <button class="bg-primary hover:bg-primary/90 text-white px-3 py-1.5 rounded text-xs font-bold flex items-center gap-1.5 transition-all">
                        <span class="material-symbols-outlined text-sm">add</span> Nouvel Article
                    </button>
                    <button class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 px-3 py-1.5 rounded text-xs font-bold border border-slate-200 dark:border-slate-700 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">file_download</span> Export
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-6 bg-slate-50 dark:bg-slate-900/50 p-3 rounded-lg border border-slate-200 dark:border-slate-800">
                <div class="md:col-span-6 relative">
                    <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                    <input class="w-full pl-8 bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded text-xs focus:ring-primary h-9" placeholder="Code ou nom d'article..." type="text" />
                </div>
                <div class="md:col-span-3">
                    <select class="w-full bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded text-xs focus:ring-primary h-9">
                        <option>Catégories</option>
                        <option>Électricité</option>
                        <option>Outillage</option>
                        <option>Fixations</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <select class="w-full bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded text-xs focus:ring-primary h-9">
                        <option>Tous Entrepôts</option>
                        <option>E1 - Nord</option>
                        <option>E2 - Sud</option>
                        <option>E3 - Central</option>
                    </select>
                </div>
            </div>
            <div class="grid-compact" id="grid-container">
                <div class="bg-white dark:bg-card-dark border border-slate-200 dark:border-slate-800 p-3 rounded-lg flex flex-col gap-2 hover:border-primary/50 transition-colors group cursor-pointer">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-mono text-primary/70 dark:text-primary/80 leading-none mb-1 uppercase">REF-8902-DX</span>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate group-hover:text-primary transition-colors">Vis à métaux M8</h3>
                    </div>
                    <div class="flex flex-col gap-1 py-2 border-y border-slate-100 dark:border-slate-800/50">
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="text-slate-500">E1: </span>
                            <span class="font-bold">120</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="text-slate-500">E2: </span>
                            <span class="font-bold">45</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-tighter">Total: 165</span>
                        <button class="text-slate-400 group-hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </button>
                    </div>
                </div>
                <div class="bg-white dark:bg-card-dark border border-slate-200 dark:border-slate-800 p-3 rounded-lg flex flex-col gap-2 hover:border-primary/50 transition-colors group cursor-pointer">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-mono text-primary/70 dark:text-primary/80 leading-none mb-1 uppercase">PRJ-4410-LED</span>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate group-hover:text-primary transition-colors">Projecteur LED 50W</h3>
                    </div>
                    <div class="flex flex-col gap-1 py-2 border-y border-slate-100 dark:border-slate-800/50">
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="text-slate-500">E1: </span>
                            <span class="font-bold">12</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="text-slate-500">E3: </span>
                            <span class="font-bold">30</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-tighter">Total: 42</span>
                        <button class="text-slate-400 group-hover:text-primary">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </button>
                    </div>
                </div>
                <div class="bg-white dark:bg-card-dark border border-slate-200 dark:border-slate-800 p-3 rounded-lg flex flex-col gap-2 hover:border-primary/50 transition-colors group cursor-pointer">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-mono text-primary/70 dark:text-primary/80 leading-none mb-1 uppercase">CAB-1022-NET</span>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate group-hover:text-primary transition-colors">Câble Ethernet 10m</h3>
                    </div>
                    <div class="flex flex-col gap-1 py-2 border-y border-slate-100 dark:border-slate-800/50">
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="text-slate-500">E2: </span>
                            <span class="font-bold">210</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] opacity-0">
                            <span class="text-slate-500">-</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-tighter">Total: 210</span>
                        <button class="text-slate-400 group-hover:text-primary">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </button>
                    </div>
                </div>
                <div class="bg-white dark:bg-card-dark border border-slate-200 dark:border-slate-800 p-3 rounded-lg flex flex-col gap-2 hover:border-primary/50 transition-colors group cursor-pointer">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-mono text-primary/70 dark:text-primary/80 leading-none mb-1 uppercase">OUT-0056-HD</span>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate group-hover:text-primary transition-colors">Perceuse Percussion</h3>
                    </div>
                    <div class="flex flex-col gap-1 py-2 border-y border-slate-100 dark:border-slate-800/50">
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="text-slate-500">E1: </span>
                            <span class="font-bold">10</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="text-slate-500">E2: </span>
                            <span class="font-bold">5</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-tighter">Total: 15</span>
                        <button class="text-slate-400 group-hover:text-primary">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Container des articles -->
            <div id="grid-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>

            <!-- Pagination -->
            <div class="mt-8 flex items-center justify-between border-t border-slate-200 dark:border-slate-800 pt-4">
                <p id="pagination-info" class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">
                    Articles: 1-16 / 1240
                </p>
                <div class="flex items-center gap-1" id="pagination">
                    <!-- Les boutons seront générés par JS -->
                </div>
            </div>
        </main>
        <footer class="mt-auto py-4 border-t border-slate-200 dark:border-slate-800 text-center">
            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">© 2024 Gestion de Stock - Haute Densité</p>
        </footer>
        <div class="fixed hidden inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4 overflow-y-auto">
            <div class="w-full max-w-4xl bg-modal-dark border border-slate-800 shadow-2xl rounded-xl flex flex-col overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="material-symbols-outlined text-primary text-xl">description</span>
                            <h2 class="text-lg font-bold text-white tracking-tight">Fiche Article Détaillée</h2>
                        </div>
                        <span class="text-xs font-mono text-slate-400 uppercase tracking-widest">CODE ARTICLE : REF-8902-DX</span>
                    </div>
                    <button class="text-slate-400 hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-8">
                    <section>
                        <h3 class="text-white font-bold text-2xl mb-2">Vis à métaux M8 - Acier Inoxydable</h3>
                        <p class="text-slate-400 text-sm leading-relaxed max-w-3xl">
                            Vis à tête hexagonale entièrement filetée conforme à la norme DIN 933. Fabriquée en acier inoxydable A2 de haute qualité pour une résistance supérieure à la corrosion. Utilisée principalement pour les assemblages mécaniques robustes en extérieur ou milieu humide. Conditionnement par boîte de 100 unités.
                        </p>
                    </section>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <section class="space-y-4">
                            <div class="flex items-center gap-2 border-b border-slate-800 pb-2">
                                <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                                <h4 class="text-xs font-black uppercase tracking-widest text-slate-300">Répartition par Emplacement</h4>
                            </div>
                            <div class="space-y-3">
                                <div class="bg-slate-900/50 rounded-lg p-3 border border-slate-800">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-bold text-white">E1 - Entrepôt Nord</span>
                                        <span class="text-xs font-black text-primary">120 Total</span>
                                    </div>
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between text-[10px] text-slate-400">
                                            <span>Rayon A - Étage 2 - Casier 45</span>
                                            <span class="font-mono text-slate-300">80</span>
                                        </div>
                                        <div class="flex justify-between text-[10px] text-slate-400">
                                            <span>Zone Réception - Palette B12</span>
                                            <span class="font-mono text-slate-300">40</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-900/50 rounded-lg p-3 border border-slate-800">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-bold text-white">E2 - Entrepôt Sud</span>
                                        <span class="text-xs font-black text-primary">45 Total</span>
                                    </div>
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between text-[10px] text-slate-400">
                                            <span>Rayon D - Étage 1 - Casier 12</span>
                                            <span class="font-mono text-slate-300">45</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="space-y-4">
                            <div class="flex items-center gap-2 border-b border-slate-800 pb-2">
                                <span class="material-symbols-outlined text-sm text-primary">history</span>
                                <h4 class="text-xs font-black uppercase tracking-widest text-slate-300">5 Derniers Mouvements</h4>
                            </div>
                            <div class="overflow-hidden rounded-lg border border-slate-800">
                                <table class="w-full text-left">
                                    <thead class="bg-slate-900/80 text-[10px] font-black uppercase text-slate-500 border-b border-slate-800">
                                        <tr>
                                            <th class="px-3 py-2">Date</th>
                                            <th class="px-3 py-2">doc</th>
                                            <th class="px-3 py-2">Type</th>
                                            <th class="px-3 py-2 text-right">Qté</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800/50 text-[10px]">
                                        <tr class="hover:bg-slate-800/30">
                                            <td class="px-3 py-2 text-slate-400">12/05/2024</td>
                                            <td class="px-3 py-2 text-slate-400">12/05/2024</td>
                                            <td class="px-3 py-2">
                                                <span class="flex items-center gap-1 text-emerald-400">
                                                    <span class="material-symbols-outlined text-[12px]">arrow_downward</span> Réception
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-right font-bold text-emerald-400">+100</td>
                                        </tr>
                                        <tr class="hover:bg-slate-800/30">
                                            <td class="px-3 py-2 text-slate-400">10/05/2024</td>
                                            <td class="px-3 py-2">
                                                <span class="flex items-center gap-1 text-red-400">
                                                    <span class="material-symbols-outlined text-[12px]">arrow_upward</span> Sortie Client
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-right font-bold text-red-400">-35</td>
                                        </tr>
                                        <tr class="hover:bg-slate-800/30">
                                            <td class="px-3 py-2 text-slate-400">08/05/2024</td>
                                            <td class="px-3 py-2">
                                                <span class="flex items-center gap-1 text-blue-400">
                                                    <span class="material-symbols-outlined text-[12px]">swap_horiz</span> Transfert E1 &gt; E2
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-right font-bold text-slate-300">15</td>
                                        </tr>
                                        <tr class="hover:bg-slate-800/30">
                                            <td class="px-3 py-2 text-slate-400">05/05/2024</td>
                                            <td class="px-3 py-2">
                                                <span class="flex items-center gap-1 text-red-400">
                                                    <span class="material-symbols-outlined text-[12px]">arrow_upward</span> Sortie Client
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-right font-bold text-red-400">-10</td>
                                        </tr>
                                        <tr class="hover:bg-slate-800/30">
                                            <td class="px-3 py-2 text-slate-400">02/05/2024</td>
                                            <td class="px-3 py-2">
                                                <span class="flex items-center gap-1 text-emerald-400">
                                                    <span class="material-symbols-outlined text-[12px]">arrow_downward</span> Réception
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-right font-bold text-emerald-400">+50</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-900/50 border-t border-slate-800 flex justify-between items-center">
                    <button class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2 rounded text-xs font-bold transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">settings</span>
                        Historique Complet
                    </button>
                    <div class="flex gap-3">
                        <button class="px-4 py-2 text-slate-400 hover:text-white text-xs font-bold transition-colors">
                            Fermer
                        </button>
                        <button class="bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded text-xs font-bold shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">edit</span>
                            Modifier l'article
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/articles/app.js"></script>
</body>

</html>