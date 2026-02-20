<!DOCTYPE html>
<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Connexion - Gestion de Stock</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec80",
                        "background-light": "#0f172a",
                        "background-dark": "#0a1120",
                        "midnight": "#020617",
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
            font-family: "Manrope", sans-serif;
        }
        .glass-effect {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .bg-pattern {
            background-image: radial-gradient(circle at 50% 50%, rgba(19, 236, 128, 0.05) 0%, transparent 50%), 
                              linear-gradient(rgba(2, 6, 23, 0.9), rgba(2, 6, 23, 0.95)), 
                              url(https://lh3.googleusercontent.com/aida-public/AB6AXuAvg0fiwkzLHJXtIwSQvW6rek9nwDEIjqOqrdWm-op2e6v0HKotl3F0PclyVXz4yznMQzqzYW_Kg8i6zfACwSDVzplN1petuBVFUe9-GFODqspW89_Qelg5tdlag-q0vzdCwIVMDx36iCrcDdJxORpShwZMezoDIJe1gFy9V2n9PKbU2mumGYMnTdzoYXJBHlvn_FLyPwJlVaa0r8DCe4i5DAWvP-xz4x9ZZRQXi0i9QskbwWDL7SN4_659L4bTPzlSTA0PYs1MV4s);
            background-size: cover;
            background-position: center;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
         @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            .spin {
                animation: spin 1s linear infinite;
            }
    </style>
</head>

<body class="bg-midnight font-display min-h-screen flex items-center justify-center p-4 bg-pattern" data-alt="Intérieur d'entrepôt sombre avec étagères floues">
    <div class="w-full max-w-md">
        <div class="flex items-center justify-center gap-3 mb-12">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 border border-primary/30 shadow-[0_0_20px_rgba(19,236,128,0.15)]">
                <span class="material-symbols-outlined text-primary text-3xl">warehouse</span>
            </div>
            <div class="flex flex-col">
                <span class="text-2xl font-bold text-white tracking-tight leading-none">STOCK<span class="text-primary">FLOW</span></span>
                <span class="text-[10px] uppercase tracking-[0.2em] text-white/40 font-semibold mt-1">Management Solutions</span>
            </div>
        </div>
        <div class="glass-effect p-8 rounded-2xl shadow-2xl border border-white/5">
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-white">Connexion</h2>
                <p class="text-white/50 text-sm mt-1">Accédez à votre espace de gestion</p>
            </div>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-white/70 mb-2" for="username">Nom d'utilisateur</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-white/30 text-[20px] group-focus-within:text-primary transition-colors">person</span>
                        </div>
                        <input class="block w-full pl-11 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200" id="username" name="username" placeholder="ex: jean.dupont" required="" type="text" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white/70 mb-2" for="password">Mot de passe</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="material-symbols-outlined text-white/30 text-[20px] group-focus-within:text-primary transition-colors">lock</span>
                        </div>
                        <input class="block w-full pl-11 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all duration-200" id="password" name="password" placeholder="••••••••" required="" type="password" />
                        <button class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-white/30 hover:text-white transition-colors" type="button">
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center cursor-pointer group">
                        <input class="rounded-md border-white/10 bg-white/5 text-primary focus:ring-primary/50 h-4 w-4 transition-all" type="checkbox" />
                        <span class="ml-2 text-white/50 group-hover:text-white/80 transition-colors">Rester connecté</span>
                    </label>
                    <a class="text-primary/90 hover:text-primary transition-colors font-medium" href="#">Mot de passe oublié ?</a>
                </div>
                <button id="loginBtn"
                    class="w-full py-4 px-4 bg-primary hover:bg-primary/90 text-midnight font-bold rounded-xl shadow-lg shadow-primary/10 transform active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 relative">
                    <span id="btnText">Se connecter</span>
                    <span id="btnLoader" class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>



                </button>


                <p id="loginMessage" class="mt-2 text-sm text-red-600"></p>
            </div>
            <div class="mt-8 pt-6 border-t border-white/5 text-center">
                <p class="text-white/40 text-sm">
                    Nouveau collaborateur ?
                    <a class="text-white/80 hover:text-primary transition-colors font-semibold ml-1" href="#">Demander un accès</a>
                </p>
            </div>
        </div>
        <div class="mt-10 flex justify-center gap-8">
            <div class="flex items-center gap-2.5">
                <span class="flex h-1.5 w-1.5 rounded-full bg-primary shadow-[0_0_8px_rgba(19,236,128,0.6)]"></span>
                <span class="text-[10px] uppercase tracking-widest text-white/30 font-bold">Serveur Actif</span>
            </div>
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[14px] text-white/30">verified_user</span>
                <span class="text-[10px] uppercase tracking-widest text-white/30 font-bold">Cryptage SSL v3</span>
            </div>
        </div>
    </div>
    <script type="module" src="/js/mouvement/login.js"></script>
</body>

</html>