<?php
// Ne pas étendre le layout par défaut pour une page minimaliste
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMVC Framework</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-accueil {
            text-align: center;
            padding: 20px;
        }

        .logo-bounce {
            width: 250px;
            height: 250px;
            margin: 0 auto 40px;
            display: inline-block;
        }

        img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-40px);
            }
        }

        .description {
            background: rgba(255, 255, 255, 0.95);
            padding: 50px 40px;
            border-radius: 15px;
            max-width: 550px;
            margin: 0 auto;
            width: fit-content;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
        }

        .description h1 {
            color: #333;
            font-size: 36px;
            margin: 0 0 20px 0;
            font-weight: bold;
        }

        .description p {
            color: #666;
            font-size: 16px;
            line-height: 1.8;
            margin: 0 0 30px 0;
        }

        .description a {
            display: inline-block;
            color: #fff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 14px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .description a:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }
    </style>
</head>

<body>
    <div class="container-accueil">


        <div class="description">
            <img src="/images/logo.png" alt="BMVC Logo">
            <h1>BMVC Framework</h1>
            <p>Framework PHP MVC moderne, professionnel et 100% en français. Développez des applications web robustes et scalables avec facilité.</p>
            <a href="https://docs.bmvc-framework.io" target="_blank">📚 Voir la Documentation</a>
        </div>
    </div>
</body>

</html>