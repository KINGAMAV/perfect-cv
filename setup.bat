@echo off
REM Script pour importer la base de données Perfect CV
REM À exécuter depuis le répertoire du projet

echo === Perfect CV - Configuration ===
echo.
echo Assurez-vous que:
echo - XAMPP est lancé (Apache + MySQL)
echo - phpMyAdmin est accessible sur http://localhost/phpmyadmin

pause

echo.
echo Début de l'importation de la base de données...
echo.

REM Chemins
set MYSQL_PATH=C:\xampp\mysql\bin\mysql
set SQL_FILE=%~dp0database\perfect_cv.sql

REM Vérifier si mysql existe
if not exist "%MYSQL_PATH%" (
    echo ERREUR: MySQL n'a pas été trouvé
    echo Vérifiez le chemin: %MYSQL_PATH%
    pause
    exit /b 1
)

REM Vérifier si le fichier SQL existe
if not exist "%SQL_FILE%" (
    echo ERREUR: Fichier SQL non trouvé
    echo Cherché à: %SQL_FILE%
    pause
    exit /b 1
)

REM Importer la base de données
echo Exécution de l'import...
"%MYSQL_PATH%" -u root -e "SOURCE %SQL_FILE%"

if %errorlevel% equ 0 (
    echo.
    echo SUCCESS: Base de données importée avec succès!
    echo.
    echo Vous pouvez maintenant accéder à:
    echo - http://localhost/perfect-cv/frontend/public/test.php
    echo - http://localhost/perfect-cv/frontend/public/index.php
    echo - http://localhost/perfect-cv/frontend/public/models.php
) else (
    echo.
    echo ERREUR: L'importation a échoué
    echo Code d'erreur: %errorlevel%
)

pause
