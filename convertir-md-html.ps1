#!/usr/bin/env pwsh
# Script de conversion Markdown -> HTML pour BMVC Documentation
# Convertit tous les .md en .html avec un design cohérent

function Convert-MarkdownToHtml {
    param(
        [string]$MarkdownPath,
        [string]$HtmlPath
    )
    
    # Lire le contenu markdown
    $content = Get-Content -Path $MarkdownPath -Raw -Encoding UTF8
    
    # Remplacer les patterns markdown par du HTML
    
    # Titres H1-H6
    $content = [regex]::Replace($content, '^### (.*?)$', '<h3>$1</h3>', [System.Text.RegularExpressions.RegexOptions]::Multiline)
    $content = [regex]::Replace($content, '^## (.*?)$', '<h2>$1</h2>', [System.Text.RegularExpressions.RegexOptions]::Multiline)
    $content = [regex]::Replace($content, '^# (.*?)$', '<h1>$1</h1>', [System.Text.RegularExpressions.RegexOptions]::Multiline)
    
    # Code blocks
    $content = [regex]::Replace($content, '```(.*?)\n(.*?)```', '<pre><code>$2</code></pre>', [System.Text.RegularExpressions.RegexOptions]::Singleline)
    
    # Bold
    $content = [regex]::Replace($content, '\*\*(.*?)\*\*', '<strong>$1</strong>')
    $content = [regex]::Replace($content, '__(.*?)__', '<strong>$1</strong>')
    
    # Italic
    $content = [regex]::Replace($content, '\*(.*?)\*', '<em>$1</em>')
    
    # Links
    $content = [regex]::Replace($content, '\[(.*?)\]\((.*?)\)', '<a href="$2">$1</a>')
    
    # Paragraphes
    $content = [regex]::Replace($content, '(?<!\n)\n(?!\n)', '</p><p>', [System.Text.RegularExpressions.RegexOptions]::Multiline)
    $content = '<p>' + $content + '</p>'
    
    # Listes
    $content = [regex]::Replace($content, '^\- (.*?)$', '<li>$1</li>', [System.Text.RegularExpressions.RegexOptions]::Multiline)
    $content = [regex]::Replace($content, '(<li>.*?</li>)', '<ul>$1</ul>')
    
    return $content
}

function Create-HtmlWrapper {
    param(
        [string]$Title,
        [string]$Content,
        [string]$HtmlPath
    )
    
    $html = @"
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$Title - BMVC Documentation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary: #1e88e5;
            --secondary: #ff6f00;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        header h1 {
            margin-bottom: 10px;
        }
        
        nav {
            background: #333;
            padding: 15px 30px;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }
        
        nav a:hover {
            text-decoration: underline;
        }
        
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1, h2, h3 {
            color: var(--primary);
            margin-top: 20px;
            margin-bottom: 10px;
        }
        
        h1 {
            font-size: 2em;
            border-bottom: 3px solid var(--secondary);
            padding-bottom: 10px;
        }
        
        h2 {
            font-size: 1.5em;
            margin-top: 30px;
        }
        
        h3 {
            font-size: 1.2em;
        }
        
        p {
            margin-bottom: 15px;
        }
        
        code {
            background: #f0f0f0;
            padding: 2px 5px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        
        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            margin: 20px 0;
        }
        
        pre code {
            background: none;
            color: inherit;
            padding: 0;
        }
        
        a {
            color: var(--primary);
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        ul, ol {
            margin-left: 30px;
            margin-bottom: 15px;
        }
        
        li {
            margin-bottom: 8px;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        
        th {
            background: var(--primary);
            color: white;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        footer {
            background: #f5f5f5;
            border-top: 1px solid #ddd;
            padding: 20px;
            text-align: center;
            color: #666;
            margin-top: 30px;
        }
        
        .breadcrumb {
            margin-bottom: 20px;
            color: #666;
        }
        
        .breadcrumb a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>📚 BMVC Framework - Documentation</h1>
        <p>Framework PHP moderne et léger</p>
    </header>
    
    <nav>
        <a href="../index.html">🏠 Accueil</a>
        <a href="../guides/getting-started/START_HERE.html">📖 Démarrage</a>
        <a href="../guides/usage/GUIDE_UTILISATION.html">📚 Utilisation</a>
        <a href="../api/Requete.html">🔌 API</a>
    </nav>
    
    <div class="container">
        <div class="breadcrumb">
            <a href="../index.html">Documentation</a> > <strong>$Title</strong>
        </div>
        
        $Content
    </div>
    
    <footer>
        <p>BMVC Framework v1.0.0 - © 2026 BMVC Framework</p>
        <p><a href="../index.html">← Retour à l'accueil</a></p>
    </footer>
</body>
</html>
"@
    
    $html | Out-File -FilePath $HtmlPath -Encoding UTF8
}

# Script principal
Write-Host "🔄 Conversion des fichiers Markdown en HTML..."
Write-Host ""

$basePath = "C:\xampp\htdocs\BMVC\docs"
$mdFiles = Get-ChildItem -Path $basePath -Filter "*.md" -Recurse

$count = 0
foreach ($mdFile in $mdFiles) {
    # Ignorer les fichiers README et INDEX
    if ($mdFile.Name -like "README*" -or $mdFile.Name -like "INDEX*" -or $mdFile.Name -like "STRUCTURE*") {
        continue
    }
    
    $htmlPath = $mdFile.FullName -replace '\.md$', '.html'
    $relPath = $mdFile.FullName -replace [regex]::Escape($basePath), ""
    
    try {
        $content = Get-Content -Path $mdFile.FullName -Raw -Encoding UTF8
        $title = ($content -split "`n")[0] -replace '^#+\s+', ''
        
        $htmlContent = Convert-MarkdownToHtml -MarkdownPath $mdFile.FullName -HtmlPath $htmlPath
        Create-HtmlWrapper -Title $title -Content $htmlContent -HtmlPath $htmlPath
        
        Write-Host "✅ Convertis: $relPath"
        $count++
    } catch {
        Write-Host "❌ Erreur: $relPath - $_"
    }
}

Write-Host ""
Write-Host "🎉 Conversion terminée!"
Write-Host "📊 $count fichiers convertis"
Write-Host ""
Write-Host "📂 Fichiers HTML créés dans:"
Write-Host "   $basePath"
