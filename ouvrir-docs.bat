@echo off
REM Ouvre la documentation dans le navigateur par défaut
REM Windows batch file

setlocal enabledelayedexpansion

REM Obtenir le chemin du script
set "script_dir=%~dp0"
set "docs_path=%script_dir%docs\index.html"

REM Vérifier si le fichier existe
if exist "%docs_path%" (
    echo Ouverture de la documentation...
    start "" "%docs_path%"
) else (
    echo Erreur: Le fichier index.html n'a pas été trouvé
    echo Chemin attendu: %docs_path%
    pause
)
