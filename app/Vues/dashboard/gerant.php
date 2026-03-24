<!DOCTYPE html>
<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tableau de Bord Financier - Détails &amp; Bénéfices</title>
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .modal-peer:checked + .modal-backdrop {
            display: flex;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-white dark:bg-surface-dark border-r border-slate-200 dark:border-border-dark hidden flex flex-col fixed h-full z-20">
            <div class="p-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">storefront</span>
                </div>
                <div>
                    <h1 class="text-sm font-bold uppercase tracking-wider text-primary">Gestion Magasin</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Analyse de Performance</p>
                </div>
            </div>
            <nav class="flex-1 px-4 space-y-2 mt-4">
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white" href="#">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-sm font-medium">Tableau de bord</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <span class="text-sm font-medium">Valorisation Stock</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                    <span class="material-symbols-outlined">payments</span>
                    <span class="text-sm font-medium">Flux Financiers</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                    <span class="material-symbols-outlined">account_balance</span>
                    <span class="text-sm font-medium">Versements</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" href="#">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <span class="text-sm font-medium">Dépenses</span>
                </a>
            </nav>
            <div class="p-4 border-t border-slate-200 dark:border-border-dark">
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-slate-300 dark:bg-slate-700 flex items-center justify-center overflow-hidden">
                        <img alt="Gérant" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBEHmYvEgIBNn4R8T-3hbhFxUperaF9LZuUOxbIVhcV3_iVkL8mFQ3MDdL342TnJqdCSYty3PIjPWoxX0mj2GtPTCSqc4_tRLRHoU8V3duGWKeeuYMc-uzmdokI4PhtjZXO2cMpIRwXKRIG-KZombEdSCwgWZWpc7ciou-w7ggdF_-1nkP7L8-qNTJ9M_1-vy6paWP_6cWCOeW5IZxHRXo76FzHAQeWOY6y5EBz9hB0mJgK1CR-GM-WjyOnf-ipPEO3Gvn0AZ-gIKI" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">Jean Dupont</p>
                        <p class="text-xs text-slate-500 truncate">Gérant Magasin #04</p>
                    </div>
                    <span class="material-symbols-outlined text-slate-400">settings</span>
                </div>
            </div>
        </aside>
        <main class="flex-1  p-8">
            <header class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight">Tableau de Bord Financier</h2>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">Analyse du Chiffre d'Affaires et des Bénéfices Réels</p>
                </div>
                <div class="flex items-center gap-4 bg-white dark:bg-surface-dark p-2 rounded-xl shadow-sm border border-slate-200 dark:border-border-dark">
                    <button class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-500 transition-colors">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <div class="flex items-center gap-4 px-2">
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Début</span>
                            <input class="bg-transparent border-none p-0 text-sm font-semibold focus:ring-0 text-slate-900 dark:text-slate-100" type="date" value="2023-11-01" />
                        </div>
                        <div class="h-8 w-px bg-slate-200 dark:bg-border-dark"></div>
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Fin</span>
                            <input class="bg-transparent border-none p-0 text-sm font-semibold focus:ring-0 text-slate-900 dark:text-slate-100" type="date" value="2023-11-30" />
                        </div>
                    </div>
                    <button class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-500 transition-colors">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </header>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-white dark:bg-surface-dark p-5 rounded-2xl border border-slate-200 dark:border-border-dark shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Stock Initial</span>
                        <span class="material-symbols-outlined text-primary text-xl">account_balance_wallet</span>
                    </div>
                    <div class="text-2xl font-bold">142,500 <span class="text-sm font-normal text-slate-500 uppercase">€</span></div>
                    <div class="mt-2 flex items-center text-xs font-medium text-emerald-500">
                        <span class="material-symbols-outlined text-sm mr-1">trending_up</span>
                        +1.2% val.
                    </div>
                </div>
                <div class="bg-white dark:bg-surface-dark p-5 rounded-2xl border border-slate-200 dark:border-border-dark shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Stock Reçu</span>
                        <span class="material-symbols-outlined text-primary text-xl">add_card</span>
                    </div>
                    <div class="text-2xl font-bold">38,200 <span class="text-sm font-normal text-slate-500 uppercase">€</span></div>
                    <div class="mt-2 flex items-center text-xs font-medium text-emerald-500">
                        <span class="material-symbols-outlined text-sm mr-1">arrow_downward</span>
                        Entrées période
                    </div>
                </div>
                <div class="bg-white dark:bg-surface-dark p-5 rounded-2xl border border-slate-200 dark:border-border-dark shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ventes (CA)</span>
                        <span class="material-symbols-outlined text-primary text-xl">point_of_sale</span>
                    </div>
                    <div class="text-2xl font-bold">58,150 <span class="text-sm font-normal text-slate-500 uppercase">€</span></div>
                    <div class="mt-2 flex items-center text-xs font-medium text-emerald-500">
                        <span class="material-symbols-outlined text-sm mr-1">trending_up</span>
                        +8.2% vs N-1
                    </div>
                </div>
                <div class="bg-white dark:bg-surface-dark p-5 rounded-2xl border border-slate-200 dark:border-border-dark shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Versements</span>
                        <span class="material-symbols-outlined text-primary text-xl">account_balance</span>
                    </div>
                    <div class="text-2xl font-bold">45,000 <span class="text-sm font-normal text-slate-500 uppercase">€</span></div>
                    <div class="mt-2 flex items-center text-xs font-medium text-rose-500">
                        <span class="material-symbols-outlined text-sm mr-1">trending_down</span>
                        -5% vs mois préc.
                    </div>
                </div>
                <div class="bg-white dark:bg-surface-dark p-5 rounded-2xl border border-slate-200 dark:border-border-dark shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Dépenses</span>
                        <span class="material-symbols-outlined text-primary text-xl">receipt</span>
                    </div>
                    <div class="text-2xl font-bold">2,300 <span class="text-sm font-normal text-slate-500 uppercase">€</span></div>
                    <div class="mt-2 flex items-center text-xs font-medium text-amber-500">
                        <span class="material-symbols-outlined text-sm mr-1">info</span>
                        OPEX Magasin
                    </div>
                </div>
            </div>
            <section class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <label class="cursor-pointer block" for="modal-ca">
                    <div class="bg-primary/10 border border-primary/20 rounded-3xl p-8 flex flex-col items-center justify-center text-center hover:bg-primary/20 transition-all group relative">
                        <span class="material-symbols-outlined absolute top-4 right-4 text-primary opacity-50 group-hover:opacity-100">info</span>
                        <div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-primary text-3xl">analytics</span>
                        </div>
                        <h3 class="text-lg font-bold text-primary uppercase tracking-widest mb-2">CHIFFRE D'AFFAIRES TOTAL</h3>
                        <div class="text-5xl font-black text-slate-900 dark:text-white mb-4">
                            58,150.00 €
                        </div>
                        <p class="max-w-xs text-slate-500 dark:text-slate-400 text-sm">
                            Volume total des ventes. Cliquez pour voir le détail par catégorie.
                        </p>
                    </div>
                </label>
                <label class="cursor-pointer block" for="modal-profit">
                    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-3xl p-8 flex flex-col items-center justify-center text-center hover:bg-emerald-500/20 transition-all group relative">
                        <span class="material-symbols-outlined absolute top-4 right-4 text-emerald-500 opacity-50 group-hover:opacity-100">info</span>
                        <div class="w-16 h-16 rounded-full bg-emerald-500/20 flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-emerald-500 text-3xl">monetization_on</span>
                        </div>
                        <h3 class="text-lg font-bold text-emerald-500 uppercase tracking-widest mb-2">BÉNÉFICE NET</h3>
                        <div class="text-5xl font-black text-slate-900 dark:text-white mb-4">
                            15,450.00 €
                        </div>
                        <p class="max-w-xs text-slate-500 dark:text-slate-400 text-sm">
                            Résultat après déduction des coûts et frais. Cliquez pour le détail du calcul.
                        </p>
                    </div>
                </label>
            </section>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <div class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-border-dark shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-border-dark flex items-center justify-between">
                        <h4 class="font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
                            Historique des Versements au Bureau
                        </h4>
                        <button class="text-primary text-sm font-bold hover:underline">Voir tout</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Référence</th>
                                    <th class="px-6 py-4 text-right">Montant</th>
                                    <th class="px-6 py-4">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-border-dark">
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium">12 Nov 2023</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">VR-2023-882</td>
                                    <td class="px-6 py-4 text-sm font-bold text-right">15,000 €</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">Confirmé</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium">19 Nov 2023</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">VR-2023-901</td>
                                    <td class="px-6 py-4 text-sm font-bold text-right">18,000 €</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">Confirmé</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="bg-white dark:bg-surface-dark rounded-2xl border border-slate-200 dark:border-border-dark shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-border-dark flex items-center justify-between">
                        <h4 class="font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-rose-500">outbox</span>
                            Historique des Dépenses Magasin
                        </h4>
                        <button class="text-primary text-sm font-bold hover:underline">Saisir une dépense</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/50 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Motif</th>
                                    <th class="px-6 py-4 text-right">Montant</th>
                                    <th class="px-6 py-4">Catégorie</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-border-dark">
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium">05 Nov 2023</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">Entretien climatisation</td>
                                    <td class="px-6 py-4 text-sm font-bold text-right">850 €</td>
                                    <td class="px-6 py-4 text-xs">Services</td>
                                </tr>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium">14 Nov 2023</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">Fournitures bureau</td>
                                    <td class="px-6 py-4 text-sm font-bold text-right">450 €</td>
                                    <td class="px-6 py-4 text-xs">Fournitures</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <input class="hidden modal-peer" id="modal-ca" type="checkbox" />
    <div class="fixed inset-0 z-50 hidden modal-backdrop items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-surface-dark w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-6 border-b border-slate-100 dark:border-border-dark flex items-center justify-between bg-primary/5">
                <h3 class="text-xl font-bold flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">analytics</span>
                    Détail du Chiffre d'Affaires
                </h3>
                <label class="cursor-pointer p-2 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-full transition-colors" for="modal-ca">
                    <span class="material-symbols-outlined">close</span>
                </label>
            </div>
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                <div class="space-y-6">
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Ventes par Catégorie</h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                <span class="text-sm font-medium">Électronique</span>
                                <span class="font-bold">24,500.00 €</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                <span class="text-sm font-medium">Mobilier</span>
                                <span class="font-bold">18,200.00 €</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                                <span class="text-sm font-medium">Accessoires</span>
                                <span class="font-bold">15,450.00 €</span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-6 border-t border-slate-100 dark:border-border-dark">
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Ventes Journalières (Top 3)</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm py-1">
                                <span class="text-slate-500">15 Nov 2023</span>
                                <span class="font-semibold">4,120.00 €</span>
                            </div>
                            <div class="flex justify-between text-sm py-1">
                                <span class="text-slate-500">12 Nov 2023</span>
                                <span class="font-semibold">3,850.00 €</span>
                            </div>
                            <div class="flex justify-between text-sm py-1">
                                <span class="text-slate-500">22 Nov 2023</span>
                                <span class="font-semibold">3,400.00 €</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-slate-50 dark:bg-slate-800/30 text-right">
                <label class="bg-primary text-white px-6 py-2 rounded-xl font-bold text-sm cursor-pointer hover:bg-primary/90 transition-all inline-block" for="modal-ca">Fermer</label>
            </div>
        </div>
    </div>
    <input class="hidden modal-peer" id="modal-profit" type="checkbox" />
    <div class="fixed inset-0 z-50 hidden modal-backdrop items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-surface-dark w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-6 border-b border-slate-100 dark:border-border-dark flex items-center justify-between bg-emerald-500/5">
                <h3 class="text-xl font-bold flex items-center gap-3">
                    <span class="material-symbols-outlined text-emerald-500">calculate</span>
                    Détail du Calcul de Bénéfice
                </h3>
                <label class="cursor-pointer p-2 hover:bg-slate-200 dark:hover:bg-slate-800 rounded-full transition-colors" for="modal-profit">
                    <span class="material-symbols-outlined">close</span>
                </label>
            </div>
            <div class="p-8">
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-3xl p-6 space-y-4">
                    <div class="flex justify-between items-center text-lg">
                        <span class="text-slate-500 font-medium">Chiffre d'Affaires (CA)</span>
                        <span class="font-bold text-primary">+ 58,150.00 €</span>
                    </div>
                    <div class="h-px bg-slate-200 dark:bg-border-dark"></div>
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-slate-500 font-medium text-sm">Coût Marchandises (COGS)</span>
                            <span class="text-[10px] text-slate-400 italic">Valeur d'achat des produits vendus</span>
                        </div>
                        <span class="font-bold text-rose-500">- 40,400.00 €</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-slate-500 font-medium text-sm">Dépenses Opérationnelles</span>
                            <span class="text-[10px] text-slate-400 italic">Frais fixes, maintenance, fournitures</span>
                        </div>
                        <span class="font-bold text-rose-500">- 2,300.00 €</span>
                    </div>
                    <div class="pt-4 border-t-2 border-dashed border-slate-200 dark:border-border-dark flex justify-between items-center">
                        <span class="text-xl font-black">BÉNÉFICE NET</span>
                        <div class="text-right">
                            <span class="text-2xl font-black text-emerald-500">15,450.00 €</span>
                            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-tighter">Marge de 26.5%</p>
                        </div>
                    </div>
                </div>
                <div class="mt-8 p-4 border-l-4 border-emerald-500 bg-emerald-500/5 rounded-r-xl">
                    <p class="text-sm text-slate-600 dark:text-slate-400 italic">
                        Le calcul est basé sur le volume de vente moins le coût de revient unitaire pondéré (CUMP) et les dépenses réelles enregistrées sur la période.
                    </p>
                </div>
            </div>
            <div class="p-6 bg-slate-50 dark:bg-slate-800/30 text-right">
                <label class="bg-emerald-500 text-white px-6 py-2 rounded-xl font-bold text-sm cursor-pointer hover:bg-emerald-600 transition-all inline-block" for="modal-profit">Compris</label>
            </div>
        </div>
    </div>

</body>

</html>