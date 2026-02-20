<!DOCTYPE html>
<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Portail Interne StockApp</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#36e27b",
                        "background-dark": "#0a131a",
                        "card-dark": "#111d26",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
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
        .bg-mesh {
            background-color: #0a131a;
            background-image: 
                radial-gradient(at 0% 0%, rgba(54, 226, 123, 0.08) 0px, transparent 40%),
                radial-gradient(at 100% 100%, rgba(54, 226, 123, 0.08) 0px, transparent 40%),
                radial-gradient(at 50% 50%, rgba(54, 226, 123, 0.03) 0px, transparent 60%);
        }
        .glass-card {
            background: rgba(17, 29, 38, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(54, 226, 123, 0.1);
        }
        .glass-card:hover {
            border-color: rgba(54, 226, 123, 0.4);
            background: rgba(17, 29, 38, 0.95);
        }
    </style>
</head>

<body class="bg-background-dark text-slate-100 min-h-screen selection:bg-primary/30">
    <div class="relative flex min-h-screen w-full flex-col bg-mesh overflow-x-hidden">
        <header class="flex items-center justify-between px-6 py-6 lg:px-20 z-50">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center size-12 rounded-xl bg-primary/10 text-primary border border-primary/20">
                    <span class="material-symbols-outlined text-3xl">inventory_2</span>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-xl font-extrabold tracking-tight text-white leading-none">StockApp</h1>
                    <span class="text-[10px] uppercase tracking-[0.2em] text-primary font-bold">Portail Interne</span>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-4 text-slate-400 text-sm">
                <span class="flex items-center gap-2"><span class="size-2 rounded-full bg-primary animate-pulse"></span> Système Opérationnel</span>
            </div>
        </header>
        <main class="flex-1 flex flex-col items-center justify-center px-6 py-8 max-w-6xl mx-auto w-full">
            <div class="text-center mb-16 space-y-4">
                <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight text-white">
                    Bienvenue sur <span class="text-primary">StockApp</span>
                </h2>
                <p class="text-slate-400 text-lg max-w-xl mx-auto">
                    Sélectionnez un module pour commencer votre session de travail.
                </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full mb-20">
                <a class="group glass-card p-10 rounded-2xl flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-2" href="/mouvements">
                    <div class="size-20 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 ring-1 ring-primary/20">
                        <span class="material-symbols-outlined text-4xl">swap_horiz</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Mouvements de Stock</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Entrées, sorties et transferts entre dépôts</p>
                </a>
                <a class="group glass-card p-10 rounded-2xl flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-2" href="#">
                    <div class="size-20 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 ring-1 ring-primary/20">
                        <span class="material-symbols-outlined text-4xl">description</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Gestion Documentaire</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Bons de livraison, factures et archives</p>
                </a>
                <a class="group glass-card p-10 rounded-2xl flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-2" href="#">
                    <div class="size-20 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 ring-1 ring-primary/20">
                        <span class="material-symbols-outlined text-4xl">inventory</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Inventaire</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">État des stocks et audits périodiques</p>
                </a>
                <a class="group glass-card p-10 rounded-2xl flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-2" href="#">
                    <div class="size-20 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 ring-1 ring-primary/20">
                        <span class="material-symbols-outlined text-4xl">admin_panel_settings</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Administration</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Utilisateurs, paramètres et permissions</p>
                </a>
            </div>
            <div class="w-full flex flex-col items-center border-t border-white/5 pt-12">
                <a href="/login" class="group relative inline-flex items-center justify-center px-16 py-5 overflow-hidden font-bold text-background-dark transition-all bg-primary rounded-xl hover:bg-primary/90 shadow-2xl shadow-primary/20 hover:scale-105 active:scale-95 duration-200">
                    <span class="relative flex items-center gap-2">
                        Se Connecter
                        <span class="material-symbols-outlined font-bold">login</span>
                    </span>
                </a>
                <p class="mt-6 text-slate-500 text-xs tracking-wide">
                    Accès restreint au personnel autorisé uniquement
                </p>
            </div>
        </main>
        <footer class="py-8 px-6 lg:px-20 mt-auto">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500 font-medium">
                <div>© 2024 StockApp v4.2.0 - Infrastructure Interne</div>
                <div class="flex items-center gap-6">
                    <a class="hover:text-primary transition-colors" href="#">Support Technique</a>
                    <a class="hover:text-primary transition-colors" href="#">Documentation</a>
                    <a class="hover:text-primary transition-colors" href="#">Sécurité</a>
                </div>
            </div>
        </footer>
    </div>

</body>

</html>