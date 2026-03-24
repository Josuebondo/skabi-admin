<!DOCTYPE html>
<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>StockFlow - Gestion Flux et Documents Stock</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#3b82f6",
                        "dark-bg": "#0f172a",
                        "dark-surface": "#1e293b",
                        "dark-border": "#334155",
                        "accent-emerald": "#10b981",
                        "accent-coral": "#fb7185",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.375rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                    },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        body {
            font-family: 'Manrope', sans-serif;
            @apply bg-dark-bg text-slate-200;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #3b82f6;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20;
        }
        .movement-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.75rem;
        }
        .document-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.70rem;
        }
                    
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            .spin {
                animation: spin 1s linear infinite;
            }


        @media (min-width: 640px) { .movement-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }  }
        @media (min-width: 1024px) { .movement-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (min-width: 1536px) { .movement-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); } }
        @media (min-width: 640px) { .document-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }  }
        @media (min-width: 1024px) { .document-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (min-width: 1536px) { .document-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); } }
    </style>
</head>

<body class="font-display">
    <div class="flex h-screen overflow-hidden">
        <aside class="hidden md:flex w-20 bg-slate-900 border-r border-dark-border flex-col items-center py-6 shrink-0">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center mb-10 shadow-lg shadow-primary/20">
                <span class="material-icons text-white">inventory_2</span>
            </div>
            <nav class="flex-1 flex flex-col gap-4">
                <a desabled class="w-12 h-12 flex items-center justify-center rounded-xl cursor-not-allowed text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" href="#" title="Tableau de bord non disponible">
                    <span class="material-icons">dashboard</span>
                    <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Tableau de bord</span>
                </a>
                <a desabled class="w-12 h-12 flex items-center cursor-not-allowed justify-center rounded-xl text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" href="#" title="Inventaire non disponible">
                    <span class="material-icons">inventory</span>
                    <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Inventaire</span>
                </a>
                <a class="w-12 h-12 flex items-center justify-center rounded-xl bg-primary/10 text-primary transition-all group relative" href="#" title="Mouvements">
                    <span class="material-icons">swap_horiz</span>
                    <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Mouvements</span>
                </a>
                <a desabled class="w-12 h-12 flex items-center cursor-not-allowed justify-center rounded-xl text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" href="#" title="Rapports non disponible">
                    <span class="material-icons">analytics</span>
                    <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Rapports</span>
                </a>
            </nav>
            <div>

                <button id="profileBtn"
                    class="mt-auto w-12 h-12 flex items-center justify-center rounded-xl hover:bg-slate-800 transition-all relative"
                    title="Profil">

                    <div id="avatarContainer"
                        class="w-10 h-10 rounded-full border-2 border-slate-700 overflow-hidden flex items-center justify-center bg-primary text-white font-bold text-sm">

                        <!-- Image si existe -->
                        <img id="avatarImg"
                            class="w-full h-full object-cover hidden"
                            alt="Profil" />

                        <!-- Initiales si pas d'image -->
                        <span id="avatarInitials"></span>

                    </div>
                </button>

                <!-- logout btn -->
                <button id="logoutBtn" class="mt-4 w-12 h-12 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" title="Déconnexion">
                    <span class="material-icons">logout</span>
                    <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Déconnexion</span>
            </div>

        </aside>
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-dark-bg">
            <header class="h-16 border-b border-dark-border flex items-center justify-between px-6 bg-slate-900/50 backdrop-blur-md shrink-0">
                <div class="flex items-center gap-4 flex-1">
                    <h1 class="text-lg font-bold text-white hidden lg:block">Gestion Flux</h1>
                    <div class="relative w-full max-w-md">
                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm">search</span>
                        <input id="search-input" class="w-full bg-slate-800 border-slate-700 text-white rounded-lg pl-9 pr-4 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-500" placeholder="Rechercher..." type="text" />
                    </div>
                </div>
                <div class="flex items-center gap-3 ml-4">
                    <div class="flex items-center gap-2 ml-4 text-[11px] text-slate-400 font-medium">
                        <span class="material-icons text-xs">access_time</span>
                        <span>Dernière actualisation : <span id="dateActualisation"></span></span>
                    </div>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-800 relative text-slate-400">
                        <span class="material-icons text-lg">notifications</span>
                        <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-accent-coral rounded-full ring-2 ring-slate-900"></span>
                    </button>
                    <button id="refresh-btn" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-2 transition-colors">
                        <span id="refresh-icon" class="material-icons text-sm">autorenew</span>
                        <span id="refresh-text">Actualiser</span>
                    </button>

                </div>
            </header>
            <div class="px-6 py-2 bg-slate-900/30 border-b border-dark-border flex gap-6 shrink-0">
                <label class="cursor-pointer py-2 text-xs font-bold transition-colors border-b-2 hover:text-primary border-primary text-primary" data-target-section="section-mouvements" id="tab-grid">
                    Mouvements
                </label>
                <label class="cursor-pointer py-2 text-xs font-bold transition-colors border-b-2 hover:text-primary border-transparent" data-target-section="section-documents" id="tab-docs">
                    Documents de Stock
                </label>
            </div>
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <section class="p-4 md:p-6 space-y-6" id="section-mouvements">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-extrabold text-white tracking-tight">Flux de Mouvements</h2>
                            <p class="text-slate-400 text-[11px] font-medium uppercase tracking-wider mt-0.5">Suivi individuel des transactions</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-slate-800 border border-dark-border rounded-lg">
                                <span class="w-1.5 h-1.5 bg-accent-emerald rounded-full"></span>
                                <span class="text-[10px] font-bold text-slate-300">124 Entrées</span>
                            </div>
                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-slate-800 border border-dark-border rounded-lg">
                                <span class="w-1.5 h-1.5 bg-accent-coral rounded-full"></span>
                                <span class="text-[10px] font-bold text-slate-300">86 Sorties</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3  bg-slate-900 border border-dark-border p-3 rounded-xl">
                        <div class="flex items-center gap-2 px-2 border-r border-slate-800">
                            <span class="material-icons text-slate-500 text-sm">calendar_today</span>
                            <select id="date-filter" class="border-none bg-transparent text-[11px] font-bold text-slate-300 focus:ring-0 p-0 pr-6">
                                <option value="" class="bg-slate-900">Tous</option>
                                <option value="1" class="bg-slate-900">Dernières 24h</option>
                                <option value="7" class="bg-slate-900">7 jours</option>
                                <option value="30" class="bg-slate-900">30 jours</option>
                                <option value="90" class="bg-slate-900">90 jours</option>
                            </select>
                        </div>

                        <div id="date-text" class="flex items-center gap-2 px-2 border-r border-slate-800">
                            <span id="date-loader" class="material-icons text-sm text-slate-500 hidden">autorenew</span>
                            <span id="date-text-content" class="text-[11px] font-bold text-slate-300">Affichage des mouvements des 7 derniers jours</span>
                        </div>
                        <button id="open-filters" class="ml-auto flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold text-slate-400 hover:bg-slate-800 rounded-lg border border-slate-700 transition-colors">
                            <span class="material-icons text-xs">filter_list</span> FILTRES
                        </button>
                    </div>
                    <div id="filters-panel" class="hidden absolute right-5 mt-2 w-80 bg-slate-900 border border-slate-700 rounded-xl shadow-xl p-4 z-50">

                        <h3 class="text-sm font-bold text-white mb-3">Filtres avancés</h3>

                        <!-- TYPE -->
                        <div class="mb-3">
                            <label class="text-xs text-slate-400">Type</label>
                            <select id="filter-type" class="w-full mt-1 bg-slate-800 border border-slate-700 rounded-lg p-2 text-xs text-white">
                                <option value="">Tous</option>
                                <option value="entrée">Entrée</option>
                                <option value="sortie">Sortie</option>
                                <option value="transfert">Transfert</option>
                            </select>
                        </div>

                        <!-- STATUT -->
                        <div class="mb-3">
                            <label class="text-xs text-slate-400">Statut</label>
                            <select id="filter-statut" class="w-full mt-1 bg-slate-800 border border-slate-700 rounded-lg p-2 text-xs text-white">
                                <option value="">Tous</option>
                                <option value="validé">Validé</option>
                                <option value="en_attente">En attente</option>
                                <option value="annulé">Annulé</option>
                            </select>
                        </div>

                        <!-- DATE PRECISE -->
                        <div class="mb-3">
                            <label class="text-xs text-slate-400">Date précise</label>
                            <input type="date" id="filter-date"
                                class="w-full mt-1 bg-slate-800 border border-slate-700 rounded-lg p-2 text-xs text-white">
                        </div>

                        <!-- ACTIONS -->
                        <div class="flex justify-between mt-4">
                            <button id="reset-filters"
                                class="px-3 py-1 text-xs rounded-lg bg-slate-700 text-white">
                                Réinitialiser
                            </button>

                            <button id="apply-filters"
                                class="px-3 py-1 text-xs rounded-lg bg-primary text-white">
                                Appliquer
                            </button>
                        </div>

                    </div>
                    <div class="movement-grid hidden" id="movement-grid">
                        <label class="bg-dark-surface border border-dark-border rounded-lg p-3 hover:border-primary/40 transition-all cursor-pointer group" data-target-section="section-details">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] font-bold text-slate-500 uppercase">24 Oct 14:22</span>
                                <span class="px-1.5 py-0.5 rounded-md bg-slate-800 text-slate-400 text-[8px] font-mono border border-slate-700">D-4421</span>
                            </div>
                            <h3 class="text-xs font-bold text-white truncate mb-2 group-hover:text-primary transition-colors">Processeur Ryzen 9</h3>
                            <div class="flex items-center gap-1.5 mb-2.5">
                                <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">Principal</span>
                                <span class="material-symbols-outlined text-xs text-slate-600">arrow_right_alt</span>
                                <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">Boutique A</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-accent-coral">-25</span>
                                <span class="material-icons text-slate-600 text-sm group-hover:translate-x-0.5 transition-transform">chevron_right</span>
                            </div>
                        </label>
                        <div class="bg-dark-surface border border-dark-border rounded-lg p-3 hover:border-primary/40 transition-all cursor-pointer group" data-target-section="section-details">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] font-bold text-slate-500 uppercase">24 Oct 11:05</span>
                                <span class="px-1.5 py-0.5 rounded-md bg-slate-800 text-slate-400 text-[8px] font-mono border border-slate-700">R-8812</span>
                            </div>
                            <h3 class="text-xs font-bold text-white truncate mb-2 group-hover:text-primary transition-colors">Souris Gaming</h3>
                            <div class="flex items-center gap-1.5 mb-2.5">
                                <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">Fournisseur</span>
                                <span class="material-symbols-outlined text-xs text-slate-600">arrow_right_alt</span>
                                <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">Principal</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-accent-emerald">+150</span>
                                <span class="material-icons text-slate-600 text-sm group-hover:translate-x-0.5 transition-transform">chevron_right</span>
                            </div>
                        </div>
                        <div class="bg-dark-surface border border-dark-border rounded-lg p-3 hover:border-primary/40 transition-all cursor-pointer group">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] font-bold text-slate-500 uppercase">23 Oct 16:45</span>
                                <span class="px-1.5 py-0.5 rounded-md bg-slate-800 text-slate-400 text-[8px] font-mono border border-slate-700">D-4419</span>
                            </div>
                            <h3 class="text-xs font-bold text-white truncate mb-2 group-hover:text-primary transition-colors">RAM 32GB</h3>
                            <div class="flex items-center gap-1.5 mb-2.5">
                                <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">Stock B</span>
                                <span class="material-symbols-outlined text-xs text-slate-600">arrow_right_alt</span>
                                <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">Hub Maint</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-slate-500">12</span>
                                <span class="material-icons text-slate-600 text-sm group-hover:translate-x-0.5 transition-transform">chevron_right</span>
                            </div>
                        </div>
                        <div class="bg-dark-surface border border-dark-border rounded-lg p-3 hover:border-primary/40 transition-all cursor-pointer group">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] font-bold text-slate-500 uppercase">23 Oct 09:15</span>
                                <span class="px-1.5 py-0.5 rounded-md bg-slate-800 text-slate-400 text-[8px] font-mono border border-slate-700">D-4418</span>
                            </div>
                            <h3 class="text-xs font-bold text-white truncate mb-2 group-hover:text-primary transition-colors">SSD 1TB</h3>
                            <div class="flex items-center gap-1.5 mb-2.5">
                                <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">Principal</span>
                                <span class="material-symbols-outlined text-xs text-slate-600">arrow_right_alt</span>
                                <span class="text-[10px] font-medium text-slate-400 truncate max-w-[50px]">Zone Est</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-accent-coral">-40</span>
                                <span class="material-icons text-slate-600 text-sm group-hover:translate-x-0.5 transition-transform">chevron_right</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-6 px-2">
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Affichage 1-10 sur 210</span>
                        <div class="flex gap-2">
                            <button id="prev" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-700 hover:bg-slate-800 text-slate-500"><span class="material-icons text-sm">chevron_left</span></button>
                            <div id="pagination" class=" flex gap-2 flex-row">
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary text-white text-[10px] font-bold">1</button>
                            </div>
                            <button id="next" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-700 hover:bg-slate-800 text-slate-500"><span class="material-icons text-sm">chevron_right</span></button>
                        </div>
                    </div>
                </section>
                <section class="p-4 md:p-6 space-y-6 hidden" id="section-documents">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-extrabold text-white tracking-tight">Documents de Stock</h2>
                            <p class="text-slate-400 text-[11px] font-medium uppercase tracking-wider mt-0.5">Regroupement par numéros de document</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 bg-slate-900 border border-dark-border p-3 rounded-xl">
                        <div class="relative">
                            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm">search</span>
                            <input id="Bsearch-input" class="w-full bg-slate-800 border-slate-700 text-white rounded-lg pl-9 pr-4 py-2 text-xs focus:ring-1 focus:ring-primary placeholder:text-slate-500" placeholder="Rechercher N° Document (ex: D-4421)" type="text" />
                        </div>
                        <div class="flex items-center gap-2 px-3 bg-slate-800 border border-slate-700 rounded-lg">
                            <span class="material-icons text-slate-500 text-sm">event</span>
                            <input class="bg-transparent border-none text-[11px] text-slate-300 focus:ring-0 w-full" type="date" />
                        </div>
                        <div class="flex items-center gap-2">
                            <select class="w-full bg-slate-800 border-slate-700 text-white rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-primary">
                                <option>Tous les types</option>
                                <option>Bon de Livraison</option>
                                <option>Bon de Sortie</option>
                                <option>Transfert Interne</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-3 document-grid" id="documents-list">
                        <div class="bg-dark-surface border border-dark-border rounded-xl p-4 hover:border-slate-500 transition-all">
                            <div class="flex items-center justify-between border-b border-dark-border pb-3 mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-primary">
                                        <span class="material-icons text-sm">description</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white">Document D-4421</h4>
                                        <p class="text-[10px] text-slate-500">24 Octobre 2023, 14:22 • Bon de Sortie</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-bold text-slate-400">3 Articles</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-accent-coral/10 text-accent-coral border border-accent-coral/20">Terminé</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-4 gap-4">
                                <div class="text-[11px]">
                                    <span class="text-slate-500 block">Origine</span>
                                    <span class="text-slate-200 font-bold">Dépôt Principal</span>
                                </div>
                                <div class="text-[11px]">
                                    <span class="text-slate-500 block">Destination</span>
                                    <span class="text-slate-200 font-bold">Boutique A</span>
                                </div>
                                <div class="text-[11px]">
                                    <span class="text-slate-500 block">Opérateur</span>
                                    <span class="text-slate-200 font-bold">Jean M.</span>
                                </div>
                                <div class="flex justify-end">
                                    <button class="text-[10px] font-bold text-primary hover:underline flex items-center gap-1" data-target-section="section-document-details">
                                        VOIR DÉTAILS <span class="material-icons text-xs">open_in_new</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                        <div class="bg-dark-surface border border-dark-border rounded-xl p-4 hover:border-slate-500 transition-all">
                            <div class="flex items-center justify-between border-b border-dark-border pb-3 mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-accent-emerald">
                                        <span class="material-icons text-sm">local_shipping</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white">Document R-8812</h4>
                                        <p class="text-[10px] text-slate-500">24 Octobre 2023, 11:05 • Réception Fournisseur</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-bold text-slate-400">12 Articles</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-accent-emerald/10 text-accent-emerald border border-accent-emerald/20">Validé</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-4 gap-4">
                                <div class="text-[11px]">
                                    <span class="text-slate-500 block">Expéditeur</span>
                                    <span class="text-slate-200 font-bold">TechSupply Inc.</span>
                                </div>
                                <div class="text-[11px]">
                                    <span class="text-slate-500 block">Destination</span>
                                    <span class="text-slate-200 font-bold">Dépôt Central</span>
                                </div>
                                <div class="text-[11px]">
                                    <span class="text-slate-500 block">N° Facture</span>
                                    <span class="text-slate-200 font-bold">F-2023-99</span>
                                </div>
                                <div class="flex justify-end">
                                    <button class="text-[10px] font-bold text-primary hover:underline flex items-center gap-1" data-target-section="section-document-details">
                                        VOIR DÉTAILS <span class="material-icons text-xs">open_in_new</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-6 px-2">
                        <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Affichage 1-10 sur 210</span>
                        <div class="flex gap-2">
                            <button id="bprev" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-700 hover:bg-slate-800 text-slate-500"><span class="material-icons text-sm">chevron_left</span></button>
                            <div id="bpagination" class=" flex gap-2 flex-row">
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary text-white text-[10px] font-bold">1</button>
                            </div>
                            <button id="bnext" class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-700 hover:bg-slate-800 text-slate-500"><span class="material-icons text-sm">chevron_right</span></button>
                        </div>
                    </div>
                </section>
                <section class="p-4 md:p-8 animate-in fade-in slide-in-from-bottom-4 duration-300 hidden" id="section-details">
                    <div class="max-w-4xl mx-auto bg-slate-900 border border-dark-border rounded-2xl overflow-hidden shadow-2xl">
                        <div class="bg-slate-800/50 p-6 flex items-center justify-between border-b border-dark-border">
                            <div class="flex items-center gap-4">
                                <label class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 cursor-pointer transition-colors" data-target-section="section-mouvements">
                                    <span class="material-icons">arrow_back</span>
                                </label>
                                <div>
                                    <h2 class="text-lg font-bold text-white leading-tight">Mouvement #MV- <span id="mvn-id"></span></h2>
                                    <h3 class="text-lg font-bold text-accent-emerald" id="mvn-type">Type de Mouvement: </h3>
                                    <p class="text-[11px] text-slate-500 uppercase tracking-widest font-bold">Référence Document : <span id="doc-id"></span></p>
                                </div>
                            </div>
                            <div class="lg:flex gap-2 hidden">
                                <button title="Impression non disponible" disabled
                                    class="px-4 py-2 cursor-not-allowed bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-icons text-sm">print</span> Imprimer
                                </button>

                                <button disabled title="Modification non disponible"
                                    class="px-4 py-2 bg-primary cursor-not-allowed hover:bg-primary/90 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-icons text-sm">edit</span> Modifier
                                </button>
                            </div>

                        </div>
                        <div class="p-8 space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-4">
                                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-dark-border pb-1">Information Produit</h3>
                                    <div class="bg-dark-bg p-4 rounded-xl border border-dark-border">
                                        <p class="text-xs text-slate-400 mb-1">Désignation</p>
                                        <p class="text-sm font-bold text-white mb-4" id="article-nom">Processeur Ryzen 9 7950X</p>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-[10px] text-slate-500">SKU</p>
                                                <p class="text-xs font-mono font-bold text-slate-300">CPU-RYZ9-<span id="sku"></span></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-slate-500">Catégorie</p>
                                                <p class="text-xs font-bold text-slate-300">Hardware</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-dark-border pb-1">Traçabilité Flux</h3>
                                    <div class="bg-dark-bg p-4 rounded-xl border border-dark-border relative">
                                        <div class="flex items-start gap-4 mb-6">
                                            <span class="material-icons  text-sm mt-0.5" id="qty-icon">remove_circle_outline</span>
                                            <div>
                                                <p class="text-xs text-slate-400 mb-0.5">Quantité Mouvementée</p>
                                                <p class="text-lg font-black text-white "><span id="qty"></span> <span class="text-[10px] font-medium text-slate-500 ml-1">Unités</span></p>
                                            </div>
                                        </div>
                                        <div class="space-y-4 relative z-10">
                                            <div class="flex items-center gap-3">
                                                <div class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center border border-dark-border">
                                                    <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-slate-500">DEPUIS</p>
                                                    <p class="text-xs font-bold text-slate-300" id="source">Stock Principal (Aisle 04)</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center border border-primary/40">
                                                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-primary">VERS</p>
                                                    <p class="text-xs font-bold text-slate-300" id="destination">Expédition Boutique A</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="absolute left-[27px] top-[68px] bottom-[30px] w-px bg-dark-border"></div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-dark-border pb-1">Détails Système</h3>
                                    <div class="bg-dark-bg p-4 rounded-xl border border-dark-border space-y-4">
                                        <div>
                                            <p class="text-[10px] text-slate-500">Créé par</p>
                                            <p class="text-xs font-bold text-slate-300" id="auteur"></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-500">Date et Heure</p>
                                            <p class="text-xs font-bold text-slate-300" id="creatdate"></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-500">Validé par</p>
                                            <p class="text-xs font-bold text-slate-300" id="validateur"></p>

                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-500">Date et Heure</p>
                                            <p class="text-xs font-bold text-slate-300" id="v-date"></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-500">Statut de Validation</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="w-2 h-2 rounded-full bg-accent-emerald"></span>
                                                <span class="text-xs font-bold " id="status">Approuvé</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-dark-border pb-1">Notes &amp; Commentaires</h3>
                                <div class="bg-dark-bg/50 p-4 rounded-xl border border-dashed border-dark-border">
                                    <p class="text-xs text-slate-400 italic" id="note">"Transfert urgent pour réapprovisionnement de la boutique A suite à une commande client importante. Emballage renforcé requis."</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-800/20 p-6 border-t border-dark-border flex justify-between items-center">
                            <div class="text-[10px] text-slate-500">
                                Dernière modification le <span id="date"></span> par <span id="user"></span>
                            </div>
                            <div class="flex gap-4">
                                <button id="delete-btn" class="px-4 py-2 bg-red-800 hover:bg-red-600 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-icons text-sm" id="d-btn-icon">delete</span> <span id="d-btntxt">SUPPRIMER</span>
                                </button>

                                <!-- <button class="text-[10px] font-bold text-slate-400 hover:text-white transition-colors">VALIDER</button> -->
                                <button id="validate-btn" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-icons text-sm " id="v-btn-icon">check</span><span id="v-btntxt"> VALIDER</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="p-4 md:p-8 animate-in fade-in slide-in-from-bottom-4 duration-300 hidden" id="section-document-details">
                    <div class="max-w-4xl mx-auto bg-slate-900 border border-dark-border rounded-2xl overflow-hidden shadow-2xl">
                        <div class="bg-slate-800/50 p-6 flex items-center justify-between border-b border-dark-border">
                            <div class="flex items-center gap-4">
                                <label class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 cursor-pointer transition-colors" data-target-section="section-documents">
                                    <span class="material-icons">arrow_back</span>
                                </label>
                                <div>
                                    <h2 class="text-lg font-bold text-white leading-tight">Document <span id="bon-doc"></span></h2>
                                    <p class="text-[11px] text-slate-500 uppercase tracking-widest font-bold">Type : <span id="bon-type"></span></p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-icons text-sm">print</span> Imprimer
                                </button>
                                <button class="px-4 py-2 bg-primary hover:bg-primary/90 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-icons text-sm">edit</span> Modifier
                                </button>
                            </div>
                        </div>
                        <div class="p-8 space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-4">
                                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-dark-border pb-1">Informations Document</h3>
                                    <div class="bg-dark-bg p-4 rounded-xl border border-dark-border space-y-4">
                                        <div>
                                            <p class="text-[10px] text-slate-500">Numéro</p>
                                            <p class="text-xs font-bold text-slate-300">D-<span id="bon-id"></span></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-500">Crée par :</p>
                                            <p class="text-xs font-bold text-slate-300"><span id="bon-createur"></span></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-500">Date et Heure</p>
                                            <p class="text-xs font-bold text-slate-300"><span id="bon-date"></span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-dark-border pb-1">Traçabilité Logistique</h3>
                                    <div class="bg-dark-bg p-4 rounded-xl border border-dark-border relative">
                                        <div class="flex items-start gap-4 mb-6">
                                            <span class="material-icons text-accent-coral text-sm mt-0.5">local_shipping</span>
                                            <div>
                                                <p class="text-xs text-slate-400 mb-0.5">Articles Concernés</p>
                                                <p class="text-lg font-black text-white"><span id="bon-qty"></span> <span class="text-[10px] font-medium text-slate-500 ml-1">Articles</span></p>
                                            </div>
                                        </div>
                                        <div class="space-y-4 relative z-10">
                                            <div class="flex items-center gap-3">
                                                <div class="w-6 h-6 rounded-full bg-slate-800 flex items-center justify-center border border-dark-border">
                                                    <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-slate-500">ORIGINE</p>
                                                    <p class="text-xs font-bold text-slate-300"><span id="bon-source"></span></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center border border-primary/40">
                                                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-primary">DESTINATION</p>
                                                    <p class="text-xs font-bold text-slate-300"><span id="bon-destination"></span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="absolute left-[27px] top-[68px] bottom-[30px] w-px bg-dark-border"></div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-dark-border pb-1">Statut &amp; Validation</h3>
                                    <div class="bg-dark-bg p-4 rounded-xl border border-dark-border space-y-4">
                                        <div>
                                            <p class="text-[10px] text-slate-500">Statut</p>
                                            <div class="flex items-center gap-2 mt-1" id="b-status-contaner">
                                                <span class="w-2 h-2 rounded-full bg-accent-emerald"></span>
                                                <span class="text-xs font-bold text-accent-emerald"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-500">Validé par :</p>
                                            <p class="text-xs font-bold text-slate-300"><span id="b-validateur"></span></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-slate-500">Date et Heure</p>
                                            <p class="text-xs font-mono font-bold text-slate-300"><span id="b-v-date"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-dark-border pb-1">Lignes de Document</h3>
                                <div id="b-items" class="bg-dark-bg/50 p-4 rounded-xl border border-dashed border-dark-border space-y-3">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-slate-300 font-semibold">Processeur Ryzen 9 7950X</span>
                                        <span class="text-accent-coral font-black">-25</span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-slate-300 font-semibold">SSD NVMe 1TB</span>
                                        <span class="text-accent-coral font-black">-10</span>
                                    </div>
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-slate-300 font-semibold">RAM DDR5 32GB</span>
                                        <span class="text-accent-coral font-black">-6</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-800/20 p-6 border-t border-dark-border flex justify-between items-center">
                            <div class="text-[10px] text-slate-500">
                                Derniére modification le 24 Oct 2023 &Agrave; 14:45 par Admin
                            </div>
                            <div class="flex gap-4">
                                <button id="b-delete-btn" class="px-4 py-2 bg-red-800 hover:bg-red-600 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-icons text-sm" id="b-d-btn-icon">delete</span> <span id="b-d-btntxt">SUPPRIMER</span>
                                </button>

                                <!-- <button class="text-[10px] font-bold text-slate-400 hover:text-white transition-colors">VALIDER</button> -->
                                <button id="b-validate-btn" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2">
                                    <span class="material-icons text-sm " id="b--v-btn-icon">check</span><span id="b-btntxt"> VALIDER</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </main>
    </div>
    <script src="js/mouvement/sections-toggle.js"></script>
    <script src="js/mouvement/Auth.js"></script>
    <script type="module" src="js/mouvement/main.js"></script>

    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3"></div>
</body>

</html>