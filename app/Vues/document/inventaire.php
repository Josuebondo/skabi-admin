<!DOCTYPE html>
<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Inventaire avec Valorisation Financière</title>
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
                        "success": "#10b981",
                        "danger": "#ef4444",
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
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
    </style>
</head>

<body class="bg-background-dark text-slate-100 antialiased h-screen flex flex-col overflow-hidden">
    <header class="h-16 shrink-0 flex items-center justify-between border-b border-border-dark px-6 bg-surface-dark/50 backdrop-blur-md z-30">
        <div class="flex items-center gap-4">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
                <span class="material-symbols-outlined text-white">inventory</span>
            </div>
            <div>
                <h1 class="text-sm font-bold uppercase tracking-wider text-primary leading-none mb-1">Stock Manager</h1>
                <p class="text-xs text-slate-400 leading-none">Inventaire &amp; Valorisation</p>
            </div>
        </div>
        <div class="flex-1 max-w-2xl px-8">
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-primary">search</span>
                <input class="w-full h-11 bg-background-dark/80 border border-border-dark rounded-xl pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all placeholder:text-slate-600" placeholder="Scanner un article ou rechercher par nom/code..." type="text" />
                <div class="absolute top-full left-0 w-full mt-2 bg-surface-dark border border-border-dark rounded-xl shadow-2xl overflow-hidden z-50 hidden group-focus-within:block">
                    <div class="p-2 border-b border-border-dark bg-background-dark/30">
                        <span class="text-[10px] font-bold text-slate-500 uppercase px-2">Résultats de recherche</span>
                    </div>
                    <div class="max-h-64 overflow-y-auto custom-scrollbar">
                        <button class="w-full flex items-center gap-4 p-3 hover:bg-primary/10 transition-colors text-left border-b border-border-dark/30">
                            <div class="h-10 w-10 rounded bg-background-dark flex items-center justify-center border border-border-dark">
                                <span class="material-symbols-outlined text-slate-500">memory</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold">Microcontrôleur STM32</p>
                                <p class="text-[10px] font-mono text-slate-500">MC-00452 • 15.50 €</p>
                            </div>
                            <span class="material-symbols-outlined text-primary">add_circle</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button class="flex items-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95">
                <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                Clôturer l'inventaire
            </button>
            <div class="h-10 w-10 rounded-full border-2 border-primary/30" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDAjGVrHYkgHlIQd7t_5I-m8MCZ6QHyDhOmfQDkaRkwjxsdDznO5_Z1yUlFH5TINPee0vg7PxRXYHUtbBWeWsgF9RJ_Vl8lsbrUnQC_NRADQ6Ri8t60KQ1YON_YvuuGAp6g0GFGyBF9zbfPc4zNtQ8BHSU-v2ddKZnjP7NvVIXDYBs4Mb1v4Rx4N2C17wKUWCLPsxodS3CkjLWGT8EV0M1oNR3p2JXHzIbKuQ1km5D4o-YsUeuHK3y-FsW3HafD0SWA8-oOkwS4Ohc'); background-size: cover;"></div>
        </div>
    </header>
    <div class="flex flex-1 overflow-hidden">
        <aside class="hidden md:flex w-20 bg-slate-900 border-r border-dark-border flex-col items-center py-6 shrink-0">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center mb-10 shadow-lg shadow-primary/20">
                <span class="material-icons text-white">inventory</span>
            </div>
            <nav class="flex-1 flex flex-col gap-4">
                <a desabled class="w-12 h-12 flex items-center justify-center rounded-xl cursor-not-allowed text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" href="/documents" title="Tableau de bord non disponible">
                    <span class="material-icons">description</span>
                    <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Documents</span>
                </a>
                <a desabled class="w-12 h-12 flex items-center  justify-center rounded-xl  bg-primary/10  text-primary transition-all group relative" href="/inventaire" title="Inventaire non disponible">
                    <span class="material-icons">inventory</span>
                    <span class="absolute left-14 bg-slate-700 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap pointer-events-none z-50">Inventaire</span>
                </a>
                <a class="w-12 h-12 flex items-center justify-center rounded-xl  text-slate-500 hover:bg-slate-800 hover:text-primary transition-all group relative" href="/mouvements" title="Mouvements">
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
                        <h2 class="text-lg font-bold">Articles à compter</h2>
                        <p class="text-xs text-slate-500">Saisie des quantités physiques et calcul de valorisation</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-success"></span>
                            <span class="text-slate-400">Conforme</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-danger"></span>
                            <span class="text-slate-400">Écart</span>
                        </div>
                    </div>
                </div>
                <div class="flex-1">
                    <table class="w-full text-left">
                        <thead class="bg-background-dark/50 text-[11px] font-bold uppercase text-slate-500 border-b border-border-dark">
                            <tr>
                                <th class="px-6 py-4">Article</th>
                                <th class="px-6 py-4">Emplacement</th>
                                <th class="px-6 py-4 text-center">Stock Théo.</th>
                                <th class="px-6 py-4 text-center">Quantité Comptée</th>
                                <th class="px-6 py-4 text-center">Écart</th>
                                <th class="px-6 py-4 text-right">Prix Unitaire</th>
                                <th class="px-6 py-4 text-right">Valeur Totale</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-dark/40">
                            <tr class="hover:bg-primary/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded bg-background-dark flex items-center justify-center">
                                            <span class="material-symbols-outlined text-sm text-slate-400">router</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium">Routeur Industriel Pro</p>
                                            <p class="text-[10px] font-mono text-slate-500">NET-WIFI-6</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-semibold px-2 py-1 bg-background-dark rounded border border-border-dark text-slate-300">C-01</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-mono text-slate-400">12</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <button class="h-8 w-8 rounded border border-border-dark bg-background-dark flex items-center justify-center hover:bg-primary/20 transition-colors">
                                            <span class="material-symbols-outlined text-sm">remove</span>
                                        </button>
                                        <input class="w-16 h-8 bg-background-dark border-border-dark rounded text-center text-sm font-bold focus:ring-primary focus:border-primary" type="number" value="12" />
                                        <button class="h-8 w-8 rounded border border-border-dark bg-background-dark flex items-center justify-center hover:bg-primary/20 transition-colors">
                                            <span class="material-symbols-outlined text-sm">add</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-success">0</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-mono text-slate-400">245,00 €</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold text-slate-100">2 940,00 €</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-primary/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded bg-background-dark flex items-center justify-center">
                                            <span class="material-symbols-outlined text-sm text-slate-400">settings_input_hdmi</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium">Câble RJ45 Cat6 10m</p>
                                            <p class="text-[10px] font-mono text-slate-500">CAB-CAT6-10</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-semibold px-2 py-1 bg-background-dark rounded border border-border-dark text-slate-300">B-12</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-mono text-slate-400">25</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <button class="h-8 w-8 rounded border border-border-dark bg-background-dark flex items-center justify-center hover:bg-primary/20 transition-colors">
                                            <span class="material-symbols-outlined text-sm">remove</span>
                                        </button>
                                        <input class="w-16 h-8 bg-background-dark border-border-dark rounded text-center text-sm font-bold focus:ring-primary focus:border-primary" type="number" value="23" />
                                        <button class="h-8 w-8 rounded border border-border-dark bg-background-dark flex items-center justify-center hover:bg-primary/20 transition-colors">
                                            <span class="material-symbols-outlined text-sm">add</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-danger">-2</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-mono text-slate-400">12,50 €</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold text-slate-100">287,50 €</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-primary/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded bg-background-dark flex items-center justify-center">
                                            <span class="material-symbols-outlined text-sm text-slate-400">bolt</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium">Adaptateur secteur 12V</p>
                                            <p class="text-[10px] font-mono text-slate-500">PWR-AD-12</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-semibold px-2 py-1 bg-background-dark rounded border border-border-dark text-slate-300">B-05</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-mono text-slate-400">50</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        <button class="h-8 w-8 rounded border border-border-dark bg-background-dark flex items-center justify-center hover:bg-primary/20 transition-colors">
                                            <span class="material-symbols-outlined text-sm">remove</span>
                                        </button>
                                        <input class="w-16 h-8 bg-background-dark border-border-dark rounded text-center text-sm font-bold focus:ring-primary focus:border-primary" type="number" value="55" />
                                        <button class="h-8 w-8 rounded border border-border-dark bg-background-dark flex items-center justify-center hover:bg-primary/20 transition-colors">
                                            <span class="material-symbols-outlined text-sm">add</span>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-success">+5</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-mono text-slate-400">18,00 €</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold text-slate-100">990,00 €</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <aside class="w-80 border-l border-border-dark bg-surface-dark/40 flex flex-col">
            <div class="p-6 space-y-8 flex-1 overflow-y-auto custom-scrollbar">
                <div class="space-y-4">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-500">Détails de l'Inventaire</h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-background-dark/50 rounded-lg border border-border-dark">
                            <label class="text-[10px] text-slate-500 uppercase font-bold">Session</label>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="material-symbols-outlined text-primary text-lg">calendar_today</span>
                                <span class="text-sm font-bold">Inventaire Annuel 2024</span>
                            </div>
                        </div>
                        <div class="p-3 bg-background-dark/50 rounded-lg border border-border-dark">
                            <label class="text-[10px] text-slate-500 uppercase font-bold">Périmètre</label>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="material-symbols-outlined text-slate-400 text-lg">warehouse</span>
                                <span class="text-sm font-semibold">Entrepôt Principal</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-500">Valorisation</h3>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="p-4 bg-primary/10 border border-primary/20 rounded-xl">
                            <label class="text-[10px] text-primary uppercase font-bold">Total Stock Compté</label>
                            <div class="text-xl font-black text-white mt-1">4 217,50 €</div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-3 bg-success/5 border border-success/20 rounded-lg">
                                <label class="text-[9px] text-success uppercase font-bold">Écarts Positifs</label>
                                <div class="text-sm font-bold text-success">+90,00 €</div>
                            </div>
                            <div class="p-3 bg-danger/5 border border-danger/20 rounded-lg">
                                <label class="text-[9px] text-danger uppercase font-bold">Écarts Négatifs</label>
                                <div class="text-sm font-bold text-danger">-25,00 €</div>
                            </div>
                        </div>
                        <div class="p-3 bg-background-dark/50 border border-border-dark rounded-lg flex justify-between items-center">
                            <span class="text-[10px] text-slate-400 uppercase font-bold">Net Financier</span>
                            <span class="text-sm font-black text-success">+65,00 €</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-500">Résumé des Écarts</h3>
                    <div class="space-y-3 px-1">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-400">Articles comptés</span>
                            <span class="font-bold">142 / 1500</span>
                        </div>
                        <div class="pt-4 border-t border-border-dark">
                            <div class="w-full bg-background-dark/50 h-2 rounded-full overflow-hidden">
                                <div class="bg-primary h-full w-[10%]"></div>
                            </div>
                            <p class="text-[10px] text-center text-slate-500 mt-2">Progression : 9.4% du stock total</p>
                        </div>
                    </div>
                </div>
                <div class="pt-2">
                    <label class="text-[10px] text-slate-500 uppercase font-bold mb-2 block px-1">Observations</label>
                    <textarea class="w-full bg-background-dark/30 border-border-dark rounded-lg text-xs p-3 focus:ring-primary h-24 transition-all" placeholder="Notez ici toute anomalie constatée..."></textarea>
                </div>
            </div>
            <div class="p-6 bg-background-dark/40 border-t border-border-dark">
                <button class="w-full py-3 rounded-lg border border-border-dark text-slate-400 text-xs font-bold hover:bg-background-dark transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">pause_circle</span>
                    Suspendre la saisie
                </button>
            </div>
        </aside>
    </div>
    <div class="fixed inset-0 -z-10 opacity-20 pointer-events-none" style="background-image: radial-gradient(#135bec 0.5px, transparent 0.5px); background-size: 24px 24px;"></div>

</body>

</html>