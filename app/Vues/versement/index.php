<?php

// dd($donnees);

$entrepots = $donnees['entrepots'];
$users = $donnees['users'];
// dd($users);
?>
<!DOCTYPE html>

<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Saisie des Flux Financiers (Responsive Admin)</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "surface-dark": "#1a2233",
                        "border-dark": "#2d3748",
                        "emerald-custom": "#10b981",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "3xl": "1.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        body {
            font-family: 'Manrope', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Hide scrollbar but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display pb-24 lg:pb-0">
    <div class="flex min-h-screen">
        <!-- Sidebar for Desktop -->
        <aside class="hidden lg:flex w-64 bg-white dark:bg-surface-dark border-r border-slate-200 dark:border-border-dark flex-col fixed h-full z-40">
            <div class="p-6 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-sm">admin_panel_settings</span>
                </div>
                <div>
                    <h1 class="text-sm font-bold uppercase tracking-wider text-primary">Admin Flux</h1>
                    <p class="text-[10px] text-slate-500 uppercase font-bold opacity-70">Panel Financier</p>
                </div>
            </div>
            <nav class="flex-1 px-4 space-y-2 mt-4">
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-sm font-medium">Tableau de bord</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white shadow-lg shadow-primary/20" href="#">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_balance_wallet</span>
                    <span class="text-sm font-medium">Saisie des Flux</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                    <span class="material-symbols-outlined">store</span>
                    <span class="text-sm font-medium">Magasins</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                    <span class="material-symbols-outlined">history</span>
                    <span class="text-sm font-medium">Audit logs</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="text-sm font-medium">Configuration</span>
                </a>
            </nav>
            <div class="p-4 border-t border-slate-200 dark:border-border-dark">
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center overflow-hidden">
                        <span class="material-symbols-outlined text-primary">person</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate text-slate-900 dark:text-white">Admin Central</p>
                        <p class="text-[10px] text-slate-500 truncate uppercase">Bureau Siège</p>
                    </div>
                </div>
            </div>
        </aside>
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col lg:ml-64 min-w-0">
            <!-- Mobile Top Bar -->
            <header class="lg:hidden sticky top-0 z-30 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-slate-200 dark:border-border-dark px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-sm">admin_panel_settings</span>
                    </div>
                    <h1 class="text-sm font-bold uppercase tracking-wider text-primary">Admin Flux</h1>
                </div>
                <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-surface-dark text-slate-600 dark:text-slate-400 active:scale-95 transition-transform">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </header>
            <main class="p-4 lg:p-8 space-y-6 lg:space-y-10 max-w-[1600px] mx-auto w-full">
                <!-- Title Section -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h2 class="text-2xl lg:text-3xl font-extrabold tracking-tight">Saisie des Flux Financiers</h2>
                        <p class="text-sm lg:text-base text-slate-500 dark:text-slate-400">Enregistrement des versements et dépenses validées des magasins.</p>
                    </div>

                    <div class="flex flex-rows justify-between gap-3">

                        <button id="new-btn" class="w-full md:w-auto  bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 lg:py-3 rounded-xl font-bold transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 active:scale-[0.98]" type="button">
                            <span class="material-symbols-outlined text-xl">add</span>
                            Nouvelle
                        </button>
                        <div class="hidden lg:flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Aujourd'hui:</span>
                            <span class="text-sm font-bold px-3 py-1 bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-lg">23 Nov 2023</span>
                        </div>
                    </div>
                </div>
                <!-- Main Grid Layout (Adaptive) -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 lg:gap-8 items-start">
                    <!-- Form Section -->
                    <section class="xl:col-span-12 hidden" id="new-modal">
                        <div class="bg-white dark:bg-surface-dark rounded-2xl lg:rounded-3xl border border-slate-200 dark:border-border-dark shadow-sm overflow-hidden">
                            <div class="p-4 lg:p-6 border-b border-slate-100 dark:border-border-dark bg-slate-50/50 dark:bg-slate-800/30 flex items-center gap-2">
                                <span class="material-symbols-outlined text-emerald-custom text-xl lg:text-2xl">add_circle</span>
                                <h3 class="font-bold text-sm lg:text-lg">Nouvelle Transaction</h3>
                            </div>
                            <form class="p-5 lg:p-8" id="form">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 lg:gap-6">
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] lg:text-xs font-bold text-slate-400 uppercase tracking-widest block ml-1">Magasin</label>
                                        <select id="entrepot" class="w-full bg-slate-50 dark:bg-background-dark border-slate-200 dark:border-border-dark rounded-xl text-sm focus:ring-primary focus:border-primary py-3 lg:py-3.5">
                                            <option value="">Sélectionner un magasin</option>
                                            <?php foreach ($entrepots as $e): ?>
                                                <option value="<?= e($e['id']) ?>"> <?= e($e['nom']) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="space-y-1.5 hidden">
                                        <label class="text-[10px] lg:text-xs font-bold text-slate-400 uppercase tracking-widest block ml-1">Type de flux</label>
                                        <select class="w-full bg-slate-50 dark:bg-background-dark border-slate-200 dark:border-border-dark rounded-xl text-sm focus:ring-primary focus:border-primary py-3 lg:py-3.5">
                                            <option value="versement">Versement reçu</option>
                                            <option value="depense">Dépense validée</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] lg:text-xs font-bold text-slate-400 uppercase tracking-widest block ml-1">Montant ($)</label>
                                        <input id="montant" class="w-full bg-slate-50 dark:bg-background-dark border-slate-200 dark:border-border-dark rounded-xl text-sm focus:ring-primary focus:border-primary py-3 lg:py-3.5 px-4" placeholder="0.00" type="number" />
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[10px] lg:text-xs font-bold text-slate-400 uppercase tracking-widest block ml-1">Date</label>
                                        <input id="date" class="w-full bg-slate-50 dark:bg-background-dark border-slate-200 dark:border-border-dark rounded-xl text-sm focus:ring-primary focus:border-primary py-3 lg:py-3.5 px-4" type="date" value="2023-11-23" />
                                    </div>
                                    <div class="space-y-1.5 hidden">
                                        <label class="text-[10px] lg:text-xs font-bold text-slate-400 uppercase tracking-widest block ml-1">Référence Document</label>
                                        <input class="w-full bg-slate-50 dark:bg-background-dark border-slate-200 dark:border-border-dark rounded-xl text-sm focus:ring-primary focus:border-primary py-3 lg:py-3.5 px-4" placeholder="Ex: VR-2023-001" type="text" />
                                    </div>
                                </div>
                                <div class="mt-6 lg:mt-8 flex justify-between ">
                                    <button id="cancel-btn" class="w-full md:w-auto bg-red-500 hover:bg-red-600 text-white px-8 py-4 lg:py-3.5 rounded-xl font-bold transition-all shadow-lg shadow-red-500/20 flex items-center justify-center gap-2 active:scale-[0.98]" type="button">
                                        <span class="material-symbols-outlined text-xl">cancel</span>
                                        Anuller
                                    </button>
                                    <button id="save-btn" class="w-full md:w-auto bg-emerald-custom hover:bg-emerald-600 text-white px-8 py-4 lg:py-3.5 rounded-xl font-bold transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 active:scale-[0.98]" type="button">
                                        <span class="material-symbols-outlined text-xl">save</span>
                                        Enregistrer la transaction
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                    <!-- List Section -->
                    <section class="xl:col-span-12 space-y-4">
                        <div class="flex items-center justify-between px-1">
                            <h4 class="font-bold flex items-center gap-2 text-slate-700 dark:text-slate-200">
                                <span class="material-symbols-outlined text-primary text-xl lg:text-2xl">history</span>
                                Dernières Saisies Réalisées
                            </h4>
                            <div class="flex gap-1">
                                <button class="p-2 text-slate-400 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-xl">filter_list</span>
                                </button>
                                <button class="p-2 text-slate-400 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-xl">file_download</span>
                                </button>
                            </div>
                        </div>

                        <!-- Content Switch: Cards for Mobile, Table for Desktop -->
                        <div class="bg-white dark:bg-surface-dark lg:rounded-3xl border border-slate-200 dark:border-border-dark shadow-sm overflow-hidden">
                            <div class="flex flex-wrap gap-4 mb-4 items-center bg-slate-50 dark:bg-slate-800/30 p-4 rounded-2xl border border-slate-200 dark:border-border-dark shadow-sm">

                                <!-- Filtre Magasin -->
                                <div class="flex flex-col">
                                    <label class="text-[10px] lg:text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Magasin</label>
                                    <select id="filter-entrepot" class="px-3 py-2 border rounded-xl bg-white dark:bg-background-dark text-sm focus:ring-1 focus:ring-primary focus:border-primary transition">
                                        <option value="">Tous les magasins</option>

                                    </select>
                                </div>

                                <!-- Filtre Type -->
                                <div class="flex flex-col">
                                    <label class="text-[10px] lg:text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Type de flux</label>
                                    <select id="filter-user" class="px-3 py-2 border rounded-xl bg-white dark:bg-background-dark text-sm focus:ring-1 focus:ring-primary focus:border-primary transition">
                                        <option value="">Tous les utilisateurs</option>

                                    </select>
                                </div>

                                <!-- Filtre Statut -->
                                <div class="flex flex-col">
                                    <label class="text-[10px] lg:text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Statut</label>
                                    <select id="filter-statut" class="px-3 py-2 border rounded-xl bg-white dark:bg-background-dark text-sm focus:ring-1 focus:ring-primary focus:border-primary transition">
                                        <option value="">Tous les statuts</option>
                                        <option value="validé">Validé</option>
                                        <option value="en_attente">En attente</option>
                                        <option value="annulé">Annulé</option>
                                    </select>
                                </div>

                                <!-- Filtre Date -->
                                <div class="flex flex-col">
                                    <label class="text-[10px] lg:text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1">Date</label>
                                    <input type="date" id="filter-date" class="px-3 py-2 border rounded-xl bg-white dark:bg-background-dark text-sm focus:ring-1 focus:ring-primary focus:border-primary transition">
                                </div>

                                <!-- Bouton Reset -->
                                <button id="reset-filters" class="flex items-center gap-1 px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-xl font-bold text-xs hover:bg-red-200 dark:hover:bg-red-800 transition">
                                    <span class="material-symbols-outlined text-sm">refresh</span>
                                    Réinitialiser
                                </button>

                            </div>
                            <!-- Table View (Hidden on mobile) -->
                            <div class=" lg:block overflow-x-auto">

                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-[11px] uppercase font-bold text-slate-400 tracking-wider">
                                            <th class="px-6 py-5">Date</th>
                                            <th class="px-6 py-5">Magasin</th>
                                            <th class="px-6 py-5">Crée par</th>
                                            <th class="px-6 py-5">Référence</th>
                                            <th class="px-6 py-5 text-right">Montant</th>
                                            <th class="px-6 py-5 text-center">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody" class="divide-y divide-slate-100 dark:divide-border-dark">
                                        <!-- Loader -->
                                        <tr class="animate-pulse">
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-24"></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-32"></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-20"></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-28"></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-20 ml-auto"></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-16 mx-auto"></div>
                                            </td>
                                        </tr>

                                        <tr class="animate-pulse">
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-20"></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-28"></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-24"></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-32"></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-24 ml-auto"></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="h-4 bg-slate-200 rounded w-20 mx-auto"></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Footer / Pagination -->
                            <div class="p-4 lg:p-6 border-t border-slate-100 dark:border-border-dark flex flex-col md:flex-row items-center justify-between gap-4">
                                <span class="text-xs text-slate-500 font-medium" id="pageInfo">Affichage de 3 transactions récentes</span>
                                <div class="flex gap-2 w-full md:w-auto">
                                    <button class="flex-1 md:px-6 py-3 lg:py-2 text-xs font-bold rounded-xl border border-slate-200 dark:border-border-dark bg-white dark:bg-surface-dark text-slate-600 dark:text-slate-400 hover:bg-slate-50 transition-colors" id="prevPage">Précédent</button>
                                    <button class="flex-1 md:px-6 py-3 lg:py-2 text-xs font-bold rounded-xl bg-primary text-white shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all" id="nextPage">Suivant</button>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
    <!-- Mobile Bottom Navigation (Hidden on Desktop) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white dark:bg-surface-dark border-t border-slate-200 dark:border-border-dark px-6 pb-6 pt-3 flex items-center justify-between">
        <a class="flex flex-col items-center gap-1 text-slate-400" href="#">
            <span class="material-symbols-outlined text-2xl">dashboard</span>
            <span class="text-[10px] font-bold uppercase tracking-tighter">Dash</span>
        </a>
        <a class="flex flex-col items-center gap-1 text-primary" href="#">
            <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">account_balance_wallet</span>
            <span class="text-[10px] font-bold uppercase tracking-tighter">Saisie</span>
        </a>
        <a class="flex flex-col items-center gap-1 text-slate-400" href="#">
            <span class="material-symbols-outlined text-2xl">store</span>
            <span class="text-[10px] font-bold uppercase tracking-tighter">Magasins</span>
        </a>
        <a class="flex flex-col items-center gap-1 text-slate-400" href="#">
            <span class="material-symbols-outlined text-2xl">history</span>
            <span class="text-[10px] font-bold uppercase tracking-tighter">Audit</span>
        </a>
        <a class="flex flex-col items-center gap-1 text-slate-400" href="#">
            <span class="material-symbols-outlined text-2xl">settings</span>
            <span class="text-[10px] font-bold uppercase tracking-tighter">Config</span>
        </a>
    </nav>

    <script src="<?= asset('/js/versement/main.js') ?>"></script>
</body>

</html>