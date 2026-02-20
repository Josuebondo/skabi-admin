<!DOCTYPE html>
<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>StockApp - Page d'erreur</title>
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
                        "background-light": "#f6f8f7",
                        "background-dark": "#0a131a",
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
        .bg-mesh {
            background-color: #0a131a;
            background-image: 
                radial-gradient(at 50% 50%, rgba(54, 226, 123, 0.08) 0px, transparent 50%),
                radial-gradient(at 0% 0%, rgba(54, 226, 123, 0.03) 0px, transparent 40%);
        }
        .error-illustration {
            background: linear-gradient(135deg, rgba(54, 226, 123, 0.1) 0%, transparent 100%);
        }
    </style>
</head>

<body class="bg-background-dark text-slate-100 min-h-screen selection:bg-primary/30">
    <div class="relative flex min-h-screen w-full flex-col bg-mesh overflow-x-hidden">
        <header class="flex items-center justify-between border-b border-primary/10 px-6 py-4 lg:px-20 backdrop-blur-md sticky top-0 z-50">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center size-10 rounded-lg bg-primary/10 text-primary">
                    <span class="material-symbols-outlined text-3xl">inventory_2</span>
                </div>
                <h2 class="text-xl font-extrabold tracking-tight text-white">StockApp</h2>
            </div>
            <nav class="hidden md:flex items-center gap-8">
                <a class="text-sm font-medium text-slate-400 hover:text-primary transition-colors" href="#">Aide &amp; Support</a>
                <button class="bg-primary hover:bg-primary/90 text-background-dark px-5 py-2 rounded-lg text-sm font-bold transition-all">
                    Se Connecter
                </button>
            </nav>
        </header>
        <main class="flex-1 flex flex-col items-center justify-center px-6 py-12 lg:py-20 max-w-4xl mx-auto w-full text-center">
            <div class="relative mb-12">
                <div class="absolute inset-0 bg-primary/20 blur-[100px] rounded-full scale-75 opacity-20"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <div class="error-illustration size-48 md:size-64 rounded-3xl border border-primary/20 flex items-center justify-center relative overflow-hidden group">
                        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at center, #36e27b 1px, transparent 1px); background-size: 20px 20px;"></div>
                        <div class="relative flex flex-col items-center">
                            <span class="material-symbols-outlined text-8xl md:text-9xl text-primary/30">warehouse</span>
                            <div class="absolute -bottom-2 right-0 bg-background-dark p-2 rounded-xl border border-primary/40 rotate-12 shadow-2xl">
                                <span class="material-symbols-outlined text-4xl md:text-5xl text-primary">package_2</span>
                            </div>
                            <div class="absolute -top-4 -left-4 bg-red-500/10 p-3 rounded-full border border-red-500/30 -rotate-12">
                                <span class="material-symbols-outlined text-3xl md:text-4xl text-red-400">warning</span>
                            </div>
                        </div>
                    </div>
                    <span class="absolute -z-10 text-[10rem] md:text-[15rem] font-black text-white/[0.03] select-none top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">404</span>
                </div>
            </div>
            <div class="space-y-6 max-w-2xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                    Oups ! Cette page est <span class="text-primary">introuvable</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-400 leading-relaxed">
                    Il semble que le mouvement que vous recherchez n'existe pas ou a été déplacé dans un autre entrepôt.
                </p>
            </div>
            <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4 w-full">
                <a class="w-full sm:w-auto bg-primary hover:bg-primary/90 text-background-dark px-10 py-4 rounded-xl text-lg font-bold transition-all transform hover:scale-105 shadow-xl shadow-primary/20 flex items-center justify-center gap-2" href="/">
                    <span class="material-symbols-outlined">home</span>
                    Retour à l'accueil
                </a>
                <a class="w-full sm:w-auto border border-primary/30 hover:bg-primary/10 text-white px-10 py-4 rounded-xl text-lg font-bold transition-all flex items-center justify-center gap-2" href="#">
                    <span class="material-symbols-outlined">contact_support</span>
                    Contacter le support
                </a>
            </div>
            <div class="mt-16 pt-8 border-t border-primary/5 w-full">
                <p class="text-sm text-slate-500 mb-4">Besoin d'aide pour localiser un colis ?</p>
                <div class="flex flex-wrap justify-center gap-6 text-xs font-semibold uppercase tracking-widest text-primary/60">
                    <a class="hover:text-primary transition-colors" href="#">Documentation</a>
                    <a class="hover:text-primary transition-colors" href="#">Statut des Services</a>
                    <a class="hover:text-primary transition-colors" href="#">FAQ</a>
                </div>
            </div>
        </main>
        <footer class="border-t border-primary/10 py-8 px-6 lg:px-20 bg-white/[0.02] backdrop-blur-md mt-auto">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3 opacity-60">
                    <span class="material-symbols-outlined text-primary text-xl">inventory_2</span>
                    <span class="font-bold text-white text-sm">StockApp</span>
                </div>
                <div class="text-xs text-slate-500">
                    © 2024 StockApp. Erreur interne de routage [Code: 404_NOT_FOUND]
                </div>
            </div>
        </footer>
    </div>

</body>

</html>