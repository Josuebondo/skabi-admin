<!DOCTYPE html>
<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Gestion des Documents - Entreprise</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#3b82f6", // Updated to a slightly brighter blue for dark mode contrast
                        "primary-dark": "#2563eb",
                        "background-light": "#f8fafc",
                        "background-dark": "#0f172a", // Slate 900
                        "surface-light": "#ffffff",
                        "surface-dark": "#1e293b", // Slate 800
                        "border-light": "#e2e8f0",
                        "border-dark": "#334155", // Slate 700
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.375rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <script>
        const URLROOT = "<?= URLROOT ?>";
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 24px;
        }

        .icon-filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .modal.loading::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(2px);
            z-index: 50;
        }

        .folder-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .folder-card {
            transition: all 0.2s;
        }

        .folder-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .toggle-arrow {
            font-size: 18px;
            transition: transform 0.2s;
        }

        .children-container {
            margin-left: 10px;
            border-left: 1px dashed #ccc;
            padding-left: 8px;
        }
    </style>

</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display flex flex-col h-screen overflow-hidden transition-colors duration-200">
    <header class="flex-none flex items-center justify-between whitespace-nowrap border-b border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark px-4 md:px-6 py-3 z-30 shadow-sm transition-colors duration-200">
        <div class="flex items-center gap-3">
            <button class="lg:hidden text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-white p-1 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <div class="flex items-center gap-3">
                <div class="size-8 flex items-center justify-center text-primary bg-primary/10 rounded-lg">
                    <span class="material-symbols-outlined icon-filled" style="font-size: 24px;">inventory_2</span>
                </div>
                <h2 class="text-slate-900 dark:text-white text-lg font-bold leading-tight hidden sm:block">Gestion Entreprise</h2>
            </div>
        </div>
        <nav class="hidden md:flex items-center gap-1">
            <a class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-colors" href="#">Tableau de bord</a>
            <a class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-colors" href="#">Stock</a>
            <a class="text-primary dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-colors" href="#">Documents</a>
            <a class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 px-3 lg:px-4 py-2 rounded-lg text-sm font-medium transition-colors" href="#">Rapports</a>
        </nav>
        <div class="flex items-center gap-2 sm:gap-4">
            <button class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border-2 border-surface-light dark:border-surface-dark"></span>
            </button>
            <button class="text-slate-500 dark:text-slate-400 hover:text-primary transition-colors p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 hidden sm:block">
                <span class="material-symbols-outlined">settings</span>
            </button>
            <div class="bg-center bg-no-repeat bg-cover rounded-full size-9 border-2 border-white dark:border-slate-600 shadow-sm cursor-pointer hover:ring-2 hover:ring-primary/50 transition-all" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuANRLD68xbjTTjmaf7f3wICQKLC9LpDEctvfDdD0C3oLj1hJKyxlKW2InpxUUTUhMb8VvOKhWWVEx3cJWCWfi6oRI3WHsMl0adkmyFzoZj522ZZMxtCktCHjgEbg8QPndq35tdGsGN2nqlKpDOeNPYQ6yJ7FoiSnuD5JtinCSFG3XQj0VvsOtnt5IefLQeMQZIoT2lnp952Mw7wOw9okvTNV9ChPYQuYtRs3yd7ywEMufYInWGekIccMvpNUUNxdgsTYJp74e-XKg");'></div>
        </div>
    </header>
    <div class="flex flex-1 overflow-hidden relative">
        <aside class="hidden lg:flex flex-col w-64 bg-surface-light dark:bg-surface-dark border-r border-border-light dark:border-border-dark overflow-y-auto transition-colors duration-200 z-20">
            <div class="p-4">
                <button class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white rounded-lg h-10 px-4 font-medium transition-colors shadow-lg shadow-blue-500/20 dark:shadow-blue-900/30">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    <span>Nouveau</span>
                </button>
            </div>
            <nav class="flex-1 px-3 pb-4 space-y-0.5">
                <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 mt-4 px-3">Emplacements</div>
                <a class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white rounded-lg group transition-colors" href="#">
                    <span class="material-symbols-outlined text-[20px] text-slate-400 group-hover:text-primary dark:group-hover:text-primary-400 transition-colors">home</span>
                    Accueil
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-sm font-medium bg-blue-50 dark:bg-blue-900/20 text-primary dark:text-blue-400 rounded-lg group transition-colors" href="<?= URLROOT ?>/document">
                    <span class="material-symbols-outlined text-[20px] icon-filled">folder</span>
                    Mes Fichiers
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white rounded-lg group transition-colors" href="#">
                    <span class="material-symbols-outlined text-[20px] text-slate-400 group-hover:text-primary dark:group-hover:text-primary-400 transition-colors">share</span>
                    Partagés
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white rounded-lg group transition-colors" href="#">
                    <span class="material-symbols-outlined text-[20px] text-slate-400 group-hover:text-primary dark:group-hover:text-primary-400 transition-colors">schedule</span>
                    Récents
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white rounded-lg group transition-colors" href="#">
                    <span class="material-symbols-outlined text-[20px] text-slate-400 group-hover:text-primary dark:group-hover:text-primary-400 transition-colors">star</span>
                    Favoris
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white rounded-lg group transition-colors" href="<?= URLROOT ?>/trush">
                    <span class="material-symbols-outlined text-[20px] text-slate-400 group-hover:text-red-500 transition-colors">delete</span>
                    Corbeille
                </a>
                <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 mt-6 px-3">Dossiers</div>
                <div id="left-folders" class="space-y-0.5 pl-3 border-l border-slate-200 dark:border-slate-700 ml-5">
                    <!-- Les dossiers seront insérés ici par JavaScript -->
                </div>
            </nav>
            <div class="p-4 border-t border-border-light dark:border-border-dark bg-slate-50 dark:bg-slate-800/50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Stockage</span>
                    <span class="text-xs font-medium text-slate-900 dark:text-white">75%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-primary h-1.5 rounded-full" style="width: 75%"></div>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-500 mt-2">15 GB utilisé sur 20 GB</p>
            </div>
        </aside>
        <main class="flex-1 flex flex-col h-full overflow-hidden bg-background-light dark:bg-background-dark relative transition-colors duration-200">
            <div class="flex-none px-4 md:px-6 py-4 border-b border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark transition-colors duration-200" id="main-header">
                <div class="flex items-center gap-1 md:gap-2 mb-3 text-xs md:text-sm overflow-x-auto whitespace-nowrap scrollbar-hide">
                    <a class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary-400 transition-colors" href="#">Documents</a>
                    <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
                    <a class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary-400 transition-colors" href="#">Comptabilité</a>
                    <span class="material-symbols-outlined text-[16px] text-slate-400">chevron_right</span>
                    <span class="text-slate-900 dark:text-white font-medium">Factures 2023</span>
                </div>
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex-1">
                        <h1 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Factures 2023</h1>
                        <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1 line-clamp-1 md:line-clamp-none">Gérez et organisez vos documents comptables pour l'année fiscale en cours.</p>
                    </div>
                    <div class="flex items-center gap-2 md:gap-3 w-full md:w-auto">
                        <button id="open-create-folder" class="flex-1 md:flex-none flex items-center justify-center gap-2 h-9 md:h-10 px-4 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700/80 hover:text-primary dark:hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-[20px]">create_new_folder</span>
                            <span class="">Dossier</span>
                        </button>
                        <button id="main-header-upload-btn" class="flex-1 md:flex-none flex items-center justify-center gap-2 h-9 md:h-10 px-4 rounded-lg bg-primary hover:bg-primary-dark text-white text-sm font-medium transition-colors shadow-sm shadow-blue-500/20 relative overflow-hidden">
                            <span class="material-symbols-outlined text-[20px]">upload_file</span>
                            <span>Téléverser</span>
                            <input id="main-header-file-input" type="file" multiple class="absolute inset-0 opacity-0 cursor-pointer" />
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex-none px-4 md:px-6 py-3 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 bg-white/50 dark:bg-surface-dark/95 backdrop-blur-sm border-b border-border-light dark:border-border-dark z-10 sticky top-0">
                <div class="relative w-full md:w-96 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400 dark:text-slate-500 text-[20px] group-focus-within:text-primary transition-colors">search</span>
                    </div>
                    <input class="block w-full pl-10 pr-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg leading-5 bg-white dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary dark:focus:border-primary sm:text-sm transition-all" placeholder="Rechercher..." type="text" />
                </div>
                <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 scrollbar-hide">
                    <button class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 border border-transparent hover:border-slate-300 dark:hover:border-slate-600 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors whitespace-nowrap">
                        Type
                        <span class="material-symbols-outlined text-[16px]">expand_more</span>
                    </button>
                    <button class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 border border-transparent hover:border-slate-300 dark:hover:border-slate-600 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors whitespace-nowrap">
                        Date
                        <span class="material-symbols-outlined text-[16px]">expand_more</span>
                    </button>
                    <button class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 border border-transparent hover:border-slate-300 dark:hover:border-slate-600 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors whitespace-nowrap">
                        Propriétaire
                        <span class="material-symbols-outlined text-[16px]">expand_more</span>
                    </button>
                    <div class="hidden sm:block w-px h-6 bg-slate-200 dark:bg-slate-700 mx-1"></div>
                    <div class="hidden sm:flex items-center gap-1">
                        <button class="p-1.5 text-slate-400 dark:text-slate-500 hover:text-primary dark:hover:text-primary-400 transition-colors rounded-md hover:bg-slate-100 dark:hover:bg-slate-800">
                            <span class="material-symbols-outlined text-[20px]">view_list</span>
                        </button>
                        <button class="p-1.5 text-primary dark:text-primary-400 bg-blue-50 dark:bg-blue-900/20 transition-colors rounded-md">
                            <span class="material-symbols-outlined text-[20px]">grid_view</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto p-4 md:p-6 scroll-smooth" id="container">
                <!-- Breadcrumb Navigation -->
                <div class="mb-4 text-sm text-slate-600 dark:text-slate-400">
                    <span id="breadcrumb-nav">Documents</span>
                </div>

                <!-- Titre du dossier actuel -->
                <div id="folder-header" class="hidden mb-6">
                    <h3 id="current-folder-title" class="text-2xl font-bold text-slate-900 dark:text-white mb-2"></h3>
                    <p id="current-folder-info" class="text-sm text-slate-500 dark:text-slate-400"></p>
                </div>

                <!-- Section Dossiers Enfants -->
                <div id="children-folders-section" class="mb-8">
                    <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">Dossiers</h3>
                    <div id="folder-container" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4 mb-8">
                        <!-- Les dossiers seront insérés ici par JavaScript -->
                    </div>
                </div>

                <!-- Section Fichiers -->
                <div id="files-section">
                    <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-4">Fichiers</h3>
                    <div class="bg-surface-light dark:bg-surface-dark border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden shadow-sm transition-colors duration-200">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700">
                                    <th class="py-3 px-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nom</th>
                                    <th class="py-3 px-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell whitespace-nowrap">Référence</th>
                                    <th class="py-3 px-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell whitespace-nowrap">Date</th>
                                    <th class="py-3 px-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell text-right">Taille</th>
                                    <th class="py-3 px-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right w-12">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="documents-table-body" class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                <!-- Les fichiers seront insérés ici par JavaScript en mode tableau -->
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-center mt-8 mb-10">
                        <button class="text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-white transition-colors py-2 px-4 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                            Charger plus de fichiers
                        </button>
                    </div>
                </div>
        </main>
    </div>
    <div aria-labelledby="modal-title" aria-modal="true" class="fixed w-fit-content modal hidden inset-0 z-[99] flex items-center justify-center p-4 sm:p-6 min-h-dvh" role="dialog">
        <div class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm transition-opacity"></div>
        <div class="relative w-full max-w-4xl transform flex flex-col bg-surface-light dark:bg-surface-dark rounded-2xl shadow-2xl transition-all max-h-[85vh] border border-slate-200 dark:border-slate-700">
            <div id="folder-modal-header" class="flex-none flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700/50">
                <div class="flex items-center gap-4">
                    <div class="size-12 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-500 dark:text-amber-400">
                        <span class="material-symbols-outlined text-3xl icon-filled">folder</span>
                    </div>
                    <div>
                        <div id="breadcrumb"></div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white" id="folder-title">Janvier 2024</h3>
                        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                            <span id="file_count">12 fichiers</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                            <span id="folder_size">45.2 MB</span>
                        </div>
                    </div>
                </div>
                <button class="p-2 close rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div id="inner-folder-container" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 md:gap-1 mb-3">
                <!-- Les fichiers du dossier seront insérés ici par JavaScript -->
            </div>
            <div class="flex-1 overflow-y-auto min-h-0">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 sticky top-0 z-10 backdrop-blur-sm">
                        <tr>
                            <th class="py-3 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-full sm:w-auto">Nom</th>
                            <th class="py-3 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">Refference</th>
                            <th class="py-3 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">Date</th>
                            <th class="py-3 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">Auteur</th>
                            <th class="py-3 px-6 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell text-right">Taille</th>
                            <th class="py-3 px-6 text-right w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="documents-table-body" class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        <!-- Les fichiers du dossier seront insérés ici par JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="flex-none p-4 md:p-6 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-surface-dark/30 rounded-b-2xl flex flex-col sm:flex-row justify-between items-center gap-4">
                <button class="w-full close close-modal-btn sm:w-auto px-4 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    Fermer
                </button>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button id="new-folder-btn" class="flex-1  sm:flex-none flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700/80 hover:text-primary dark:hover:text-white transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">create_new_folder</span>
                        <span class="whitespace-nowrap">Nouveau dossier</span>
                    </button>
                    <button
                        id="uploadBtn"
                        class="upload-file-btn flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white relative overflow-hidden">
                        <span class="material-symbols-outlined">upload_file</span>
                        Téléverser
                        <input
                            id="fileInput"
                            type="file"
                            multiple
                            class="absolute inset-0 opacity-0 cursor-pointer" />
                    </button>

                    <!-- Aperçu -->
                    <div id="uploadPreview" class="mt-4 space-y-2"></div>


                </div>
            </div>
        </div>
    </div>
    <!-- Modal Créer un Dossier -->
    <div id="create-folder-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[100]">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg w-96 p-6 relative">
            <h2 class="text-lg font-semibold mb-4 text-slate-900 dark:text-white">Créer un nouveau dossier</h2>

            <form id="create-folder-form" class="flex flex-col gap-4">
                <input
                    type="text"
                    id="folder-name"
                    placeholder="Nom du dossier"
                    class="border rounded px-3 py-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                    required />
                <textarea
                    id="folder-description"
                    placeholder="Description (optionnelle)"
                    class="border rounded px-3 py-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white"></textarea>

                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" id="cancel-folder-btn" class="px-4 py-2 rounded bg-gray-300 dark:bg-slate-600 hover:bg-gray-400 dark:hover:bg-slate-500">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 rounded bg-primary hover:bg-primary-dark text-white">
                        Créer
                    </button>
                </div>
                <input type="hidden" value="" id="parent-id">
            </form>

            <button id="close-folder-modal" class="absolute top-2 right-2 text-slate-500 hover:text-red-500 text-xl">&times;</button>
        </div>
    </div>

    <!-- Modal Verrouiller Dossier -->
    <div id="lock-folder-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[100]">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg w-96 p-6 relative">
            <h2 class="text-lg font-semibold mb-4 text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-yellow-500">lock</span>
                Verrouiller le dossier
            </h2>

            <form id="lock-folder-form" class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mot de passe</label>
                    <input
                        type="password"
                        id="lock-password"
                        placeholder="Entrez un mot de passe"
                        class="w-full border rounded px-3 py-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                        required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Confirmer</label>
                    <input
                        type="password"
                        id="lock-password-confirm"
                        placeholder="Confirmez le mot de passe"
                        class="w-full border rounded px-3 py-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                        required />
                </div>

                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" class="cancel-lock-btn px-4 py-2 rounded bg-gray-300 dark:bg-slate-600 hover:bg-gray-400 dark:hover:bg-slate-500">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 rounded bg-yellow-600 hover:bg-yellow-700 text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">lock</span>
                        Verrouiller
                    </button>
                </div>
                <input type="hidden" id="lock-folder-id">
            </form>

            <button class="cancel-lock-btn absolute top-2 right-2 text-slate-500 hover:text-red-500 text-xl">&times;</button>
        </div>
    </div>

    <!-- Modal Déverrouiller Dossier -->
    <div id="unlock-folder-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-[100]">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg w-96 p-6 relative">
            <h2 class="text-lg font-semibold mb-4 text-slate-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-yellow-500">lock_open</span>
                Dossier verrouillé
            </h2>

            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                Ce dossier est protégé. Entrez le mot de passe pour y accéder.
            </p>

            <form id="unlock-folder-form" class="flex flex-col gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Mot de passe</label>
                    <input
                        type="password"
                        id="unlock-password"
                        placeholder="Entrez le mot de passe"
                        class="w-full border rounded px-3 py-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                        required />
                </div>

                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" class="cancel-unlock-btn px-4 py-2 rounded bg-gray-300 dark:bg-slate-600 hover:bg-gray-400 dark:hover:bg-slate-500">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">lock_open</span>
                        Ouvrir
                    </button>
                </div>
                <input type="hidden" id="unlock-folder-id">
            </form>

            <button class="cancel-unlock-btn absolute top-2 right-2 text-slate-500 hover:text-red-500 text-xl">&times;</button>
        </div>
    </div>


    <script>
        /*
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.querySelector('[role="dialog"]');
            const openModalButtons = document.querySelectorAll('button[title="Ouvrir le dossier"]');
            const closeModalButton = modal.querySelector('button[title="close"]');
            const closeModalFooterButton = modal.querySelector('button:contains("Fermer")');

            openModalButtons.forEach(button => {
                button.addEventListener('click', () => {
                    modal.classList.remove('hidden');
                });
            });

            closeModalButton.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            closeModalFooterButton.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });*/
    </script>
    <script src="<?= URLROOT ?>/js/admin/document.js"></script>
</body>

</html>