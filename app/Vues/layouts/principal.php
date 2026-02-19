<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre ?? 'BMVC') ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        nav {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0 20px;
        }

        nav ul {
            list-style: none;
            display: flex;
            max-width: 1000px;
            margin: 0 auto;
        }

        nav li {
            margin: 0 20px;
        }

        nav a {
            display: block;
            padding: 15px 0;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #764ba2;
        }

        main {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        footer {
            background: white;
            margin-top: 60px;
            padding: 40px 20px;
            text-align: center;
            color: #666;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <nav>
        <ul>
            <li><a href="/">BMVC</a></li>
            <li><a href="/auth/login">Connexion</a></li>
        </ul>
    </nav>

    <main>
        <?= $contenu ?? '' ?>
    </main>

    <footer>
        <p>&copy; 2026 BMVC - Framework web français</p>
    </footer>
</body>

</html>