<h1 align="center">Who's Julia ?</h1>

Ce projet tutoré est encadré lors de la licence Informatique de l'Université de la Nouvelle-Calédonie ([**UNC**](https://unc.nc/)) lors du deuxième semestre de l'année 2025.  

Développé par [**Alexandre JAMROZ**](https://github.com/Alexandre-JAMROZ) et [**Lucas JAMET**](https://github.com/LJS78).

**[Licence du projet](https://github.com/Alexandre-JAMROZ/who-s-julia/blob/main/LICENSE)**.

<h2 align="center">Description du projet</h3>

Le projet a pour objectif de développer un site Internet et d'apprendre un langage de programmation.

[**Julia**](https://julialang.org/) est un jeune langage (2012) développé par le MIT. [**Julia**](https://julialang.org/) se veut aussi performant que [**C++**](https://fr.wikipedia.org/wiki/C%2B%2B) mais aussi simple à écrire que le [**Python**](https://fr.wikipedia.org/wiki/Python_(langage)). Julia concurrence également Python sur le développement de l'IA. Il faudra en apprendre les bases et être capable de synthétiser cette apprentissage pour la restituer sous forme de quiz.

Le site internet prendra la forme d'un jeu de rôle textuel dans lequel chaque niveau correspond à une étape d'apprentissage du langage Julia. Les premiers niveaux seront donc dédiés aux principes de bases, puis les niveaux suivants ameneront vers des fonctionnalités avancées.

La thématique du jeu sera développée par un stage en parallèle, effectué par un·e étudiant·e de la licence SHS Culture Océanienne. 

<h2 align="center">Pré-requis pour lancer le projet</h2>

Premièrement, il nous faut le logiciel [**XAMPP**](https://www.apachefriends.org/fr/index.html). Ce logiciel permet d'héberger le site en local ainsi que de créer des bases de données. Pour une expérience plus réaliste, il faudra changer des propriétés dans les fichiers du logiciel.
- Installer [**XAMPP**](https://www.apachefriends.org/fr/download.html)
- Placer le dossier du projet dans le chemin :
    - Par exemple : ```"C:\xampp\htdocs\who-s-julia"```
    - Par convention, un projet créer avec XAMPP doit être placé dans ce chemin de répertoire.
- Dans le fichier ```"C:\xampp\apache\httpd.conf"``` :
    - A la ligne 252 et 253, remplacer : 
    ```  
    DocumentRoot "C:/xampp/htdocs"  
    <Directory "C:/xampp/htdocs">
    ```
    - par :
    ```
    DocumentRoot "C:/xampp/htdocs/who-s-julia/public"
    <Directory "C:/xampp/htdocs/who-s-julia/public">
    ```
    - Cela permet de diriger la racine du site vers ```/public```
    - Suite à cela, dans la barre de recherche, on aura :
        - ```localhost``` à la page d'accueil par exemple plutôt que
        - ```localhost/who-s-julia/public```
    - Pourquoi modifier ?
        - Pour une expérience plus réaliste, expliqué ci-dessus
        - Cela empêche à l'utilisateur d'accéder à des fichiers **backend**
        - *Si vous avez d'autres projets que vous voulez utiliser avec XAMPP entre-temps, rechanger les paramètres. Vous ne pourrez plus exécuter le projet tant que les propriétés du fichier sont par défaut.*

Ensuite, il faut configurer la base de données. Sur XAMPP, il faut cliquer sur ```Start``` dans la ligne ```MySQL``` puis ensuite cliquer sur ```Admin```.
Cela lancera une page en ```localhost/phpmyadmin/``` pour configurer des bases de données :
- Dans la bannière en haut, cliquer sur ```SQL```
- Dans le champ, coller ce [**script**](https://github.com/Alexandre-JAMROZ/who-s-julia/blob/main/db/schema.sql) et cliquer sur ```Exécuter```
    - Cela va créer la base de données avec toutes les relations et tables nécessaires
    - */!\ Bien vérifier que la base de données se nomme ```julia``` auquel cas le projet ne marchera pas**