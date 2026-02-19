<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titre ?? 'Accueil') ?> - BMVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            transition: all 0.3s ease;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        main {
            flex: 1;
            padding: 30px 0;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .navbar-brand {
            font-size: 1.5em;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            height: 45px;
            width: auto;
            object-fit: contain;
        }

        .nav-link {
            margin: 0 10px;
            font-weight: 500;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #fff;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        footer {
            background: rgba(255, 255, 255, 0.95);
            border-top: 2px solid #667eea;
            margin-top: auto;
            padding: 30px 0;
            box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.05);
        }

        footer p {
            font-weight: 500;
            color: #666;
        }

        /* Cartes */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none;
            padding: 20px !important;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Boutons */
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 10px 25px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(17, 153, 142, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(235, 51, 73, 0.3);
        }

        /* Formulaires */
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background-color: #fff;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            font-size: 0.9em;
            margin-top: 5px;
        }

        /* Badge */
        .badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Liens */
        a {
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-link {
                margin: 5px 0;
            }

            .card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <img src="<?php echo url('/images/logo.png'); ?>" alt="BMVC Logo"> BMVC
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="fas fa-home"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/articles"><i class="fas fa-newspaper"></i> Articles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact"><i class="fas fa-envelope"></i> Contact</a>
                    </li>
                    <?php if (est_connecte()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/profil"><i class="fas fa-user-circle"></i> Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="/deconnexion"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/connexion"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/inscription"><i class="fas fa-user-plus"></i> Inscription</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Messages Flash -->
    <div class="container mt-3">
        <?php if (isset($_SESSION['flash'])): ?>
            <?php foreach ($_SESSION['flash'] as $type => $message): ?>
                <div class="alert alert-<?= $type === 'succes' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?= $type === 'succes' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <?= e($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
    </div>

    <!-- Contenu principal -->
    <main class="py-5">
        <div class="container">
            <?php section('contenu'); ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center py-4 mt-5 bg-dark text-white">
        <div class="container">
            <p class="mb-0">
                <i class="fas fa-code"></i> BMVC Framework - PHP MVC moderne et sécurisé
                <br>
                <small>&copy; 2024 - Tous droits réservés</small>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>