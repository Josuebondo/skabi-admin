<!DOCTYPE html>
<html class="dark" lang="fr">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?= $data['titre'] ?> </title>
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
    <?php
    core\Vue::section('header');

    ?>
    <?php

    echo $this instanceof \Core\Vue ? $this->inclure('layouts.sideBare') : ''


    ?>
    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">

        <?php
        core\Vue::section('contenu');

        ?>
    </div>

    <script src="js/document/modal.js"></script>

</body>

</html>