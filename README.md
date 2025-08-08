# who-s-julia
Dépôt du projet tutoré en licence d'informatique par Alexandre JAMROZ et Lucas JAMET

## Présentation du projet
Le projet a pour objectif de déveloper un site internet ET d'apprendre un langage de programmation. 

Le langage à apprendre est le Julia, un jeune langage (2012) développé par le MIT. Julia se veut aussi performant que C++mais aussi simple à écrire que le Python. Julia concurrence également Python sur le développement de l'IA. Il faudra en apprendre les base et être capable de synthétiser cette apprentissage pour la restituer sous forme de quizz.

Le site internet prendra la forme d'un jeu de rôle textuel dans lequel chaque niveau correspond à une étape d'apprentissage du langage Julia. Les premiers niveaux seront donc dédiés aux principes de bases, puis les niveaux suivants ameneront vers des fonctionnalités avancées.
La thématique du jeu sera développée par un stage en parallèle, effectué par un·e étudiant·e de la licence SHS Culture Océanienne. 

## Pré-requis
Pour que le site fonctionne et que la base de données soit initialisée, il faut :
- Installer XAMPP
    - Pour gérer le lancement du serveur ainsi que la base de données.
    - Une fois le logiciel lancé, il faut lancer Apache et MySQL avec ```Start```.
- Placer le dossier du projet dans le chemin :
    - Par exemple : ```"C:\xampp\htdocs\who-s-julia"```
    - Cela permet de transiter des données entre le site et la base de données.
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
    - Cela permet de diriger la racine du site vers ```/public```.