<!DOCTYPE html>
<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Création Document - Prix et Totaux</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#0b0e14",
                        "surface-dark": "#161b26",
                        "border-dark": "#282e39",
                        "emerald-accent": "#10b981",
                    },
                    fontFamily: {
                        "display": ["Manrope"]
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
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #282e39;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #135bec;
        }
                /* Cacher les flèches sur Chrome, Edge, Safari */
        .no-spinner::-webkit-outer-spin-button,
        .no-spinner::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Cacher les flèches sur Firefox */
        .no-spinner {
        -moz-appearance: textfield;
        }
    </style>
</head>

<body class="bg-background-dark text-slate-100 antialiased h-screen flex flex-col overflow-hidden">
    <header class="h-16 shrink-0 flex items-center justify-between border-b border-border-dark px-6 bg-surface-dark/50 backdrop-blur-md z-30">
        <div class="flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
                <span class="material-symbols-outlined text-white">inventory_2</span>
            </div>
            <div>
                <h1 class="text-sm font-bold uppercase tracking-wider text-primary leading-none mb-1">Stock Manager</h1>
                <p class="text-xs text-slate-400 leading-none">Gestion de Stock v2.1</p>
            </div>
        </div>
        <div class="flex-1 max-w-2xl px-8">
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-primary">search</span>
                <input id="search-input"
                    class="w-full h-11 bg-background-dark/80 border border-border-dark rounded-xl pl-12 pr-4 text-sm"
                    placeholder="Scanner un article ou rechercher par nom/code..."
                    type="text" />

                <div id="search-suggestions"
                    class="absolute top-full left-0 w-full mt-2 bg-surface-dark border border-border-dark rounded-xl shadow-2xl overflow-hidden z-50 hidden">

                    <div class="p-2 border-b border-border-dark bg-background-dark/30">
                        <span class="text-[10px] font-bold text-slate-500 uppercase px-2">
                            Résultats de recherche
                        </span>
                    </div>

                    <div id="suggestions-list" class=""></div>

                </div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button id="new-doc-btn" class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Nouveau Document
            </button>
            <button class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                Valider le Document
            </button>
            <div class="h-10 w-10 rounded-full border-2 border-primary/30" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDAjGVrHYkgHlIQd7t_5I-m8MCZ6QHyDhOmfQDkaRkwjxsdDznO5_Z1yUlFH5TINPee0vg7PxRXYHUtbBWeWsgF9RJ_Vl8lsbrUnQC_NRADQ6Ri8t60KQ1YON_YvuuGAp6g0GFGyBF9zbfPc4zNtQ8BHSU-v2ddKZnjP7NvVIXDYBs4Mb1v4Rx4N2C17wKUWCLPsxodS3CkjLWGT8EV0M1oNR3p2JXHzIbKuQ1km5D4o-YsUeuHK3y-FsW3HafD0SWA8-oOkwS4Ohc'); background-size: cover;"></div>
        </div>
    </header>
    <div class="flex flex-1 overflow-hidden">
        <div id="new-doc-modal" class="fixed hidden inset-0 z-[100] flex items-center justify-center p-4 bg-background-dark/80 backdrop-blur-sm">
            <div class="w-full max-w-lg bg-surface-dark border border-border-dark rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-8 border-b border-border-dark flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-primary/20 flex items-center justify-center border border-primary/30">
                        <span class="material-symbols-outlined text-primary text-3xl">note_add</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-white">Nouveau Document</h2>
                        <p class="text-sm text-slate-400">
                            Configurez les paramètres initiaux
                        </p>
                    </div>
                    <!-- bouton close -->
                    <button id="closeModalBtn" class="ml-auto text-slate-500 hover:text-slate-300 transition-colors">
                        <span class="material-symbols-outlined">close</span>
                </div>

                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black uppercase tracking-widest text-slate-500">Type de Document</label>
                        <div class="relative group">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-primary pointer-events-none">description</span>
                            <select
                                class="w-full h-12 bg-background-dark/50 border border-border-dark rounded-xl pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all appearance-none cursor-pointer">
                                <option value="">Sélectionner un type...</option>
                                <option value="transfert">Transfert Inter-Stock</option>
                                <option value="reception">Réception Fournisseur</option>
                                <option value="expedition">Expédition Client</option>
                                <option value="inventaire">Ajustement Inventaire</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label
                            class="text-[10px] font-black uppercase tracking-widest text-slate-500">Date d'activation</label>
                        <div class="relative group">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-primary pointer-events-none">calendar_today</span>
                            <input
                                class="w-full h-12 bg-background-dark/50 border border-border-dark rounded-xl pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                type="date"
                                value="2023-10-27" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-500">Source (Origine)</label>
                            <div class="relative group">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-primary pointer-events-none">warehouse</span>
                                <select
                                    class="w-full h-12 bg-background-dark/50 border border-border-dark rounded-xl pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all appearance-none cursor-pointer">
                                    <option value="">Choisir...</option>
                                    <option value="lyon">Entrepôt Lyon</option>
                                    <option value="paris">Entrepôt Paris</option>
                                    <option value="marseille">Magasin Marseille</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-slate-500">Destination (Cible)</label>
                            <div class="relative group">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-primary pointer-events-none">location_on</span>
                                <select
                                    class="w-full h-12 bg-background-dark/50 border border-border-dark rounded-xl pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all appearance-none cursor-pointer">
                                    <option value="">Choisir...</option>
                                    <option value="paris">Entrepôt Paris</option>
                                    <option value="lyon">Entrepôt Lyon</option>
                                    <option value="lille">Boutique Lille</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="p-8 bg-background-dark/30 border-t border-border-dark flex gap-4">
                    <button
                        class="flex-1 h-12 bg-emerald-accent hover:bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">play_arrow</span>
                        Commencer la saisie
                    </button>
                </div>
            </div>
        </div>
        <aside class="hidden md:flex w-20 bg-slate-900 border-r border-dark-border flex-col items-center py-6 shrink-0">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center mb-10 shadow-lg shadow-primary/20">
                <span class="material-icons text-white">inventory</span>
            </div>
            <nav class="flex-1 flex flex-col gap-4">
                <a desabled class="w-12 h-12 flex items-center justify-center rounded-xl bg-primary/10 text-primary  transition-all group relative" href="/documents" title="Tableau de bord non disponible">
                    <span class="material-icons">description</span>
                    <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Documents</span>
                </a>
                <a desabled class="w-12 h-12 flex items-center cursor-not-allowed justify-center rounded-xl text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" href="/inventaire" title="Inventaire non disponible">
                    <span class="material-icons">inventory</span>
                    <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Inventaire</span>
                </a>
                <a class="w-12 h-12 flex items-center justify-center rounded-xl   text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" href="/mouvements" title="Mouvements">
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
        <main class="flex-1 overflow-y-auto custom-scrollbar p-6">
            <div class="bg-surface-dark border border-border-dark rounded-xl shadow-xl flex flex-col min-h-full">
                <div class="p-4 border-b border-border-dark flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold">Articles du Document</h2>
                        <p class="text-xs text-slate-500">Saisie des quantités et valorisation unitaire</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-400 bg-background-dark px-3 py-1 rounded-full border border-border-dark">2 types d'articles</span>
                    </div>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead class="bg-background-dark/50 text-[11px] font-bold uppercase text-slate-500 border-b border-border-dark sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-4">Article</th>
                                <th class="px-6 py-4">Code</th>
                                <th class="px-6 py-4 text-center">Quantité</th>
                                <th class="px-6 py-4">n° reçu</th>
                                <th class="px-6 py-4 text-right">Prix Unit. (€)</th>
                                <th class="px-6 py-4 text-right">Total HT (€)</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-dark/40">

                            <tr class="hover:bg-primary/5 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded bg-background-dark flex items-center justify-center">
                                            <span class="material-symbols-outlined text-sm text-slate-400">settings_input_hdmi</span>
                                        </div>
                                        <span class="text-sm font-medium">Câble RJ45 Cat6 10m</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-primary">CAB-CAT6-10</td>
                                <td class="px-6 py-4" id="qtytd">
                                    <div class="flex  items-center justify-center">
                                        <input id="qty-input" class="w-20 hidden  no-spinner h-8 bg-background-dark border-border-dark rounded text-center text-sm focus:ring-primary focus:border-primary" type="number" value="" />
                                        <span id="qty-value" class=" text-sm   font-medium text-center"> 3</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4" id="R-td">
                                    <div class="flex  items-center justify-center">
                                        <input id="R-input" class="w-20 hidden no-spinner h-8 bg-background-dark border-border-dark rounded text-center text-sm focus:ring-primary focus:border-primary" type="number" value="" />
                                        <span id="R-value" class=" text-sm   font-medium text-center"> 3</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    12,20
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-slate-100">
                                    305,00
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-slate-500 hover:text-red-500 transition-colors">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <aside class="w-80 border-l border-border-dark bg-surface-dark/40 flex flex-col shrink-0">
            <div class="p-6 space-y-8 flex-1 overflow-y-auto custom-scrollbar">
                <div class="space-y-4">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-500">Informations Générales</h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-background-dark/50 rounded-lg border border-border-dark">
                            <label class="text-[10px] text-slate-500 uppercase font-bold">Date d'activité</label>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="material-symbols-outlined text-primary text-lg">calendar_today</span>
                                <span class="text-sm font-bold">Transfert Inter-Stock</span>
                            </div>
                        </div>
                        <div class="p-3 bg-background-dark/50 rounded-lg border border-border-dark">
                            <label class="text-[10px] text-slate-500 uppercase font-bold">Type de Document</label>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="material-symbols-outlined text-primary text-lg">move_item</span>
                                <span class="text-sm font-bold">Transfert Inter-Stock</span>
                            </div>
                        </div>
                        <div class="p-3 bg-background-dark/50 rounded-lg border border-border-dark">
                            <label class="text-[10px] text-slate-500 uppercase font-bold">Source</label>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="material-symbols-outlined text-slate-400 text-lg">warehouse</span>
                                <span class="text-sm font-semibold">Entrepôt Lyon Principal</span>
                            </div>
                        </div>
                        <div class="p-3 bg-background-dark/50 rounded-lg border border-border-dark">
                            <label class="text-[10px] text-slate-500 uppercase font-bold">Destination</label>
                            <div class="flex items-center gap-2 mt-1 text-primary">
                                <span class="material-symbols-outlined text-lg">forward</span>
                                <span class="text-sm font-semibold">Magasin Paris Sud</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-500">Résumé Document</h3>
                    <div class="space-y-3 px-1">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400">Total Articles</span>
                            <span class="font-bold">27 unités</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400">Poids Estimé</span>
                            <span class="font-bold">14.5 kg</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-border-dark/30">
                            <span class="text-primary font-bold text-sm uppercase">Valeur Totale</span>
                            <span class="text-lg font-black text-white">596,00 €</span>
                        </div>
                        <div class="pt-4 border-t border-border-dark">
                            <label class="text-[10px] text-slate-500 uppercase font-bold mb-2 block">Commentaire</label>
                            <textarea class="w-full bg-background-dark/30 border-border-dark rounded-lg text-xs p-3 focus:ring-primary h-24" placeholder="Note interne..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-background-dark/40 border-t border-border-dark flex flex-raw gap-2">
                <button class="w-full py-3 rounded-lg border border-red-500/30 text-red-500 text-xs font-bold hover:bg-red-500/10 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">cancel</span>
                    Annuler
                </button>
                <button class="w-full py-3 rounded-lg border border-green-500/30 text-green-500 text-xs font-bold hover:bg-green-500/10 transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">save</span>
                    Sauvegarder
                </button>
            </div>
        </aside>
    </div>
    <div class="fixed inset-0 -z-10 opacity-20 pointer-events-none" style="background-image: radial-gradient(#135bec 0.5px, transparent 0.5px); background-size: 24px 24px;"></div>
    <script src="js/document/modal.js"></script>
    <script src="js/document/app.js"></script>
</body>

</html>