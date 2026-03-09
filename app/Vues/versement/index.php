<!DOCTYPE html>
<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Saisie des Flux Financiers (Admin)</title>
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
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-white dark:bg-surface-dark border-r border-slate-200 dark:border-border-dark flex flex-col fixed h-full z-20">
            <div class="p-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined">admin_panel_settings</span>
                </div>
                <div>
                    <h1 class="text-sm font-bold uppercase tracking-wider text-primary leading-tight">Admin Flux</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Panel Financier</p>
                </div>
            </div>
            <nav class="flex-1 px-4 space-y-2 mt-4">
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white" href="#">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                    <span class="text-sm font-medium">Saisie des Flux</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                    <span class="material-symbols-outlined">store</span>
                    <span class="text-sm font-medium">Liste des Magasins</span>
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
                    <div class="w-8 h-8 rounded-full bg-slate-300 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                        <img alt="Admin" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDgj6ttZZ-x8Q3VC27Gye0gl_1enM-ETawOteFTJmA3RwJBjjNMkSk4l3CEGbNUEf2SmCO7mBMlfPrMDBOtw6p6vdxL_Gv2xO4MzUiiOPqpPA0hULFrhKZ9kHiW5_EKrfcGKU93EO_tL3q_eD5ehe9o1NnIgDacxnBMnhsAgCGqwL_EgDD4r-CzlEox2TPJt7-QWN97WxbKKdnvUaVhQSAj2zwW3IcBZBfwBmwO8ooj8C1l4V0CfcwsUiynw_zYuR7fuqZb1PW98m8" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate text-slate-900 dark:text-white">Admin Central</p>
                        <p class="text-xs text-slate-500 truncate">Bureau Siège</p>
                    </div>
                </div>
            </div>
        </aside>
        <main class="flex-1 ml-64 p-8">
            <header class="mb-8">
                <h2 class="text-3xl font-extrabold tracking-tight">Saisie des Flux Financiers</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Enregistrement des versements et dépenses validées des magasins.</p>
            </header>
            <section class="mb-10">
                <div class="bg-white dark:bg-surface-dark rounded-3xl border border-slate-200 dark:border-border-dark shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-border-dark bg-slate-50/50 dark:bg-slate-800/30">
                        <h3 class="font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-emerald-custom">add_circle</span>
                            Nouvelle Transaction
                        </h3>
                    </div>
                    <form class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Magasin</label>
                                <select class="w-full bg-slate-50 dark:bg-background-dark border-slate-200 dark:border-border-dark rounded-xl text-sm focus:ring-primary focus:border-primary py-3">
                                    <option value="">Sélectionner un magasin</option>
                                    <option>Magasin #01 - Paris Centre</option>
                                    <option>Magasin #02 - Lyon Part-Dieu</option>
                                    <option>Magasin #03 - Marseille Vieux-Port</option>
                                    <option>Magasin #04 - Bordeaux Lac</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Type de flux</label>
                                <select class="w-full bg-slate-50 dark:bg-background-dark border-slate-200 dark:border-border-dark rounded-xl text-sm focus:ring-primary focus:border-primary py-3">
                                    <option value="versement">Versement reçu</option>
                                    <option value="depense">Dépense validée</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Montant (€)</label>
                                <input class="w-full bg-slate-50 dark:bg-background-dark border-slate-200 dark:border-border-dark rounded-xl text-sm focus:ring-primary focus:border-primary py-3 px-4" placeholder="0.00" type="number" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Date de l'opération</label>
                                <input class="w-full bg-slate-50 dark:bg-background-dark border-slate-200 dark:border-border-dark rounded-xl text-sm focus:ring-primary focus:border-primary py-3 px-4" type="date" value="2023-11-23" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Référence Document</label>
                                <input class="w-full bg-slate-50 dark:bg-background-dark border-slate-200 dark:border-border-dark rounded-xl text-sm focus:ring-primary focus:border-primary py-3 px-4" placeholder="Ex: VR-2023-001" type="text" />
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button class="bg-emerald-custom hover:bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-emerald-500/20 flex items-center gap-2 group" type="button">
                                <span class="material-symbols-outlined text-lg group-hover:scale-110 transition-transform">save</span>
                                Enregistrer la transaction
                            </button>
                        </div>
                    </form>
                </div>
            </section>
            <section>
                <div class="bg-white dark:bg-surface-dark rounded-3xl border border-slate-200 dark:border-border-dark shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-border-dark flex items-center justify-between">
                        <h4 class="font-bold flex items-center gap-2 text-slate-700 dark:text-slate-200">
                            <span class="material-symbols-outlined text-primary">history</span>
                            Dernières Saisies Réalisées
                        </h4>
                        <div class="flex gap-2">
                            <button class="p-2 text-slate-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">filter_list</span>
                            </button>
                            <button class="p-2 text-slate-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined">file_download</span>
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Magasin</th>
                                    <th class="px-6 py-4">Type</th>
                                    <th class="px-6 py-4">Référence</th>
                                    <th class="px-6 py-4 text-right">Montant</th>
                                    <th class="px-6 py-4">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-border-dark">
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium">23 Nov 2023</td>
                                    <td class="px-6 py-4 text-sm">Paris Centre (#01)</td>
                                    <td class="px-6 py-4">
                                        <span class="flex items-center gap-1.5 text-xs font-semibold text-emerald-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Versement
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 font-mono uppercase">VR-2023-1122</td>
                                    <td class="px-6 py-4 text-sm font-bold text-right">12,500.00 €</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">Validé</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium">22 Nov 2023</td>
                                    <td class="px-6 py-4 text-sm">Lyon Part-Dieu (#02)</td>
                                    <td class="px-6 py-4">
                                        <span class="flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Dépense
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 font-mono uppercase">EXP-2023-45</td>
                                    <td class="px-6 py-4 text-sm font-bold text-right">350.00 €</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">Validé</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium">22 Nov 2023</td>
                                    <td class="px-6 py-4 text-sm">Bordeaux Lac (#04)</td>
                                    <td class="px-6 py-4">
                                        <span class="flex items-center gap-1.5 text-xs font-semibold text-emerald-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Versement
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 font-mono uppercase">VR-2023-1120</td>
                                    <td class="px-6 py-4 text-sm font-bold text-right">8,200.00 €</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">En cours</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium">21 Nov 2023</td>
                                    <td class="px-6 py-4 text-sm">Marseille VP (#03)</td>
                                    <td class="px-6 py-4">
                                        <span class="flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Dépense
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 font-mono uppercase">EXP-2023-44</td>
                                    <td class="px-6 py-4 text-sm font-bold text-right">1,200.00 €</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">Validé</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100 dark:border-border-dark flex items-center justify-between">
                        <span class="text-xs text-slate-500">Affichage de 4 transactions récentes</span>
                        <div class="flex gap-2">
                            <button class="px-3 py-1 text-xs font-bold rounded-lg border border-slate-200 dark:border-border-dark hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Précédent</button>
                            <button class="px-3 py-1 text-xs font-bold rounded-lg bg-primary text-white hover:bg-primary/90 transition-all">Suivant</button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

</body>

</html>