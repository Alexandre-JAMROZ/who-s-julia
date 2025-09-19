-- Base de données Julia - Version corrigée avec JSON valide
-- Suppression des tables existantes si elles existent
DROP TABLE IF EXISTS `exercices_users`;
DROP TABLE IF EXISTS `exercices`;
DROP TABLE IF EXISTS `modules`;
DROP TABLE IF EXISTS `users`;

-- Structure de la table `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `pseudo` (`pseudo`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Structure de la table `modules`
CREATE TABLE `modules` (
  `id_module` int(11) NOT NULL AUTO_INCREMENT,
  `nom_module` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Structure de la table `exercices`
CREATE TABLE `exercices` (
  `id_exercice` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `enonce` text NOT NULL,
  `test_cases` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`test_cases`)),
  `type_exercice` enum('output','function','mixed') DEFAULT 'function',
  PRIMARY KEY (`id_module`,`id_exercice`),
  CONSTRAINT `exercices_ibfk_1` FOREIGN KEY (`id_module`) REFERENCES `modules` (`id_module`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Structure de la table `exercices_users`
CREATE TABLE `exercices_users` (
  `id_user` int(11) NOT NULL,
  `id_module` int(11) NOT NULL,
  `id_exercice` int(11) NOT NULL,
  `code_soumis` text DEFAULT NULL,
  `est_reussi` tinyint(1) DEFAULT 0,
  `date_soumission` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_user`,`id_module`,`id_exercice`),
  KEY `id_module` (`id_module`,`id_exercice`),
  CONSTRAINT `exercices_users_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exercices_users_ibfk_2` FOREIGN KEY (`id_module`,`id_exercice`) REFERENCES `exercices` (`id_module`, `id_exercice`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertion des modules
INSERT INTO `modules` (`id_module`, `nom_module`, `description`) VALUES
(1, 'Les Bases', 'Découvrez les fondamentaux de Julia : variables, types et opérations de base'),
(2, 'Fonctions et Calculs', 'Maîtrisez les fonctions et les opérations mathématiques en Julia'),
(3, 'Structures de Données', 'Explorez les structures de données : tableaux, tuples et dictionnaires'),
(4, 'Conditions et Boucles', 'Apprenez à contrôler le flux de votre programme'),
(5, 'Manipulation de Chaînes', 'Travaillez avec les chaînes de caractères');

-- MODULE 1: Les Bases
INSERT INTO `exercices` (`id_exercice`, `id_module`, `titre`, `enonce`, `test_cases`, `type_exercice`) VALUES
(1, 1, 'Hello Julia!', 'Écrivez un programme qui affiche "Hello Julia!" en utilisant la fonction println()', 
'[{"expected_output":"Hello Julia!"}]', 'output'),

(2, 1, 'Variables et affichage', 'Créez une variable nommée "message" contenant le texte "Bienvenue dans Julia" et affichez-la avec println()', 
'[{"expected_output":"Bienvenue dans Julia","test_type":"variable_check","required_vars":["message"]}]', 'output'),

(3, 1, 'Fonction simple', 'Créez une fonction nommée "dire_bonjour" qui prend un paramètre "nom" et retourne la chaîne "Bonjour, " suivie du nom.\n\nExemple:\ndire_bonjour("Alice") # retourne "Bonjour, Alice"', 
'[{"function_call":"dire_bonjour(\\"Alice\\")","expected_output":"Bonjour, Alice"},{"function_call":"dire_bonjour(\\"Bob\\")","expected_output":"Bonjour, Bob"},{"function_call":"dire_bonjour(\\"Julia\\")","expected_output":"Bonjour, Julia"}]', 'function'),

(4, 1, 'Fonction addition', 'Créez une fonction "addition" qui prend deux nombres et retourne leur somme.\n\nExemple:\naddition(5, 3) # retourne 8', 
'[{"function_call":"addition(2, 3)","expected_output":"5"},{"function_call":"addition(10, 15)","expected_output":"25"},{"function_call":"addition(-5, 5)","expected_output":"0"},{"function_call":"addition(0, 0)","expected_output":"0"}]', 'function'),

(5, 1, 'Type de données', 'Créez une fonction "type_nombre" qui prend un nombre et retourne "entier" si c\'est un entier, "décimal" sinon.\n\nExemple:\ntype_nombre(5) # retourne "entier"\ntype_nombre(5.5) # retourne "décimal"',
'[{"function_call":"type_nombre(5)","expected_output":"entier"},{"function_call":"type_nombre(5.5)","expected_output":"décimal"},{"function_call":"type_nombre(10)","expected_output":"entier"},{"function_call":"type_nombre(3.14)","expected_output":"décimal"}]', 'function');

-- MODULE 2: Fonctions et Calculs
INSERT INTO `exercices` (`id_exercice`, `id_module`, `titre`, `enonce`, `test_cases`, `type_exercice`) VALUES
(1, 2, 'Fonction carré', 'Créez une fonction "carre" qui prend un nombre et retourne son carré.\n\nExemple:\ncarre(4) # retourne 16', 
'[{"function_call":"carre(4)","expected_output":"16"},{"function_call":"carre(5)","expected_output":"25"},{"function_call":"carre(-3)","expected_output":"9"},{"function_call":"carre(0)","expected_output":"0"},{"function_call":"carre(10)","expected_output":"100"}]', 'function'),

(2, 2, 'Fonction puissance', 'Créez une fonction "puissance" qui prend deux nombres (base et exposant) et retourne base^exposant.\n\nExemple:\npuissance(2, 3) # retourne 8',
'[{"function_call":"puissance(2, 3)","expected_output":"8"},{"function_call":"puissance(5, 2)","expected_output":"25"},{"function_call":"puissance(10, 0)","expected_output":"1"},{"function_call":"puissance(3, 4)","expected_output":"81"}]', 'function'),

(3, 2, 'Calculer moyenne', 'Créez une fonction "moyenne" qui prend trois nombres et retourne leur moyenne.\n\nExemple:\nmoyenne(10, 20, 30) # retourne 20.0',
'[{"function_call":"moyenne(10, 20, 30)","expected_output":"20.0"},{"function_call":"moyenne(5, 5, 5)","expected_output":"5.0"},{"function_call":"moyenne(0, 0, 0)","expected_output":"0.0"},{"function_call":"moyenne(1, 2, 3)","expected_output":"2.0"}]', 'function'),

(4, 2, 'Distance entre points', 'Créez une fonction "distance" qui calcule la distance entre deux points dans un plan 2D.\nLa fonction prend 4 paramètres: x1, y1, x2, y2 et retourne la distance euclidienne.\n\nExemple:\ndistance(0, 0, 3, 4) # retourne 5.0',
'[{"function_call":"distance(0, 0, 3, 4)","expected_output":"5.0"},{"function_call":"distance(1, 1, 1, 1)","expected_output":"0.0"},{"function_call":"distance(0, 0, 1, 1)","expected_output":"1.4142135623730951"},{"function_call":"distance(2, 3, 5, 7)","expected_output":"5.0"}]', 'function'),

(5, 2, 'Factorielle', 'Créez une fonction récursive "factorielle" qui calcule la factorielle d\'un nombre.\n\nExemple:\nfactorielle(5) # retourne 120',
'[{"function_call":"factorielle(5)","expected_output":"120"},{"function_call":"factorielle(0)","expected_output":"1"},{"function_call":"factorielle(1)","expected_output":"1"},{"function_call":"factorielle(3)","expected_output":"6"},{"function_call":"factorielle(7)","expected_output":"5040"}]', 'function'),

(6, 2, 'Fonction modulo', 'Créez une fonction "reste" qui prend deux nombres et retourne le reste de leur division.\n\nExemple:\nreste(17, 5) # retourne 2',
'[{"function_call":"reste(17, 5)","expected_output":"2"},{"function_call":"reste(20, 4)","expected_output":"0"},{"function_call":"reste(13, 3)","expected_output":"1"},{"function_call":"reste(100, 7)","expected_output":"2"}]', 'function');

-- MODULE 3: Structures de Données
INSERT INTO `exercices` (`id_exercice`, `id_module`, `titre`, `enonce`, `test_cases`, `type_exercice`) VALUES
(1, 3, 'Somme de tableau', 'Créez une fonction "somme_tableau" qui prend un tableau de nombres et retourne leur somme.\n\nExemple:\nsomme_tableau([1, 2, 3, 4]) # retourne 10',
'[{"function_call":"somme_tableau([1, 2, 3, 4])","expected_output":"10"},{"function_call":"somme_tableau([5, 5, 5])","expected_output":"15"},{"function_call":"somme_tableau([0])","expected_output":"0"},{"function_call":"somme_tableau([-1, 1, -2, 2])","expected_output":"0"}]', 'function'),

(2, 3, 'Maximum d\'un tableau', 'Créez une fonction "max_tableau" qui trouve et retourne le plus grand élément d\'un tableau.\n\nExemple:\nmax_tableau([3, 7, 2, 9, 1]) # retourne 9',
'[{"function_call":"max_tableau([3, 7, 2, 9, 1])","expected_output":"9"},{"function_call":"max_tableau([5])","expected_output":"5"},{"function_call":"max_tableau([-1, -5, -2])","expected_output":"-1"},{"function_call":"max_tableau([100, 99, 101])","expected_output":"101"}]', 'function'),

(3, 3, 'Longueur personnalisée', 'Créez une fonction "compter_elements" qui compte le nombre d\'éléments dans un tableau sans utiliser length().\n\nExemple:\ncompter_elements([1, 2, 3]) # retourne 3',
'[{"function_call":"compter_elements([1, 2, 3])","expected_output":"3"},{"function_call":"compter_elements([])","expected_output":"0"},{"function_call":"compter_elements([5])","expected_output":"1"},{"function_call":"compter_elements([1, 2, 3, 4, 5, 6])","expected_output":"6"}]', 'function'),

(4, 3, 'Inverser tableau', 'Créez une fonction "inverser" qui prend un tableau et retourne un nouveau tableau avec les éléments dans l\'ordre inverse.\n\nExemple:\ninverser([1, 2, 3]) # retourne [3, 2, 1]',
'[{"function_call":"inverser([1, 2, 3])","expected_output":"[3, 2, 1]"},{"function_call":"inverser([5])","expected_output":"[5]"},{"function_call":"inverser([\\"a\\", \\"b\\", \\"c\\"])","expected_output":"[\\"c\\", \\"b\\", \\"a\\"]"},{"function_call":"inverser([])","expected_output":"[]"}]', 'function'),

(5, 3, 'Fusionner tuples', 'Créez une fonction "fusionner_tuples" qui prend deux tuples et retourne un nouveau tuple contenant tous leurs éléments.\n\nExemple:\nfusionner_tuples((1, 2), (3, 4)) # retourne (1, 2, 3, 4)',
'[{"function_call":"fusionner_tuples((1, 2), (3, 4))","expected_output":"(1, 2, 3, 4)"},{"function_call":"fusionner_tuples((5,), (6,))","expected_output":"(5, 6)"},{"function_call":"fusionner_tuples((), (1, 2))","expected_output":"(1, 2)"},{"function_call":"fusionner_tuples((\\"a\\",), (\\"b\\", \\"c\\"))","expected_output":"(\\"a\\", \\"b\\", \\"c\\")"}]', 'function');

-- MODULE 4: Conditions et Boucles
INSERT INTO `exercices` (`id_exercice`, `id_module`, `titre`, `enonce`, `test_cases`, `type_exercice`) VALUES
(1, 4, 'Nombre pair ou impair', 'Créez une fonction "pair_impair" qui prend un nombre et retourne "pair" s\'il est pair, "impair" sinon.\n\nExemple:\npair_impair(4) # retourne "pair"',
'[{"function_call":"pair_impair(4)","expected_output":"pair"},{"function_call":"pair_impair(7)","expected_output":"impair"},{"function_call":"pair_impair(0)","expected_output":"pair"},{"function_call":"pair_impair(-3)","expected_output":"impair"}]', 'function'),

(2, 4, 'Valeur absolue', 'Créez une fonction "valeur_absolue" qui retourne la valeur absolue d\'un nombre sans utiliser abs().\n\nExemple:\nvaleur_absolue(-5) # retourne 5',
'[{"function_call":"valeur_absolue(-5)","expected_output":"5"},{"function_call":"valeur_absolue(5)","expected_output":"5"},{"function_call":"valeur_absolue(0)","expected_output":"0"},{"function_call":"valeur_absolue(-100)","expected_output":"100"}]', 'function'),

(3, 4, 'Compter jusqu\'à n', 'Créez une fonction "compter" qui prend un nombre n et retourne un tableau contenant les nombres de 1 à n.\n\nExemple:\ncompter(5) # retourne [1, 2, 3, 4, 5]',
'[{"function_call":"compter(5)","expected_output":"[1, 2, 3, 4, 5]"},{"function_call":"compter(1)","expected_output":"[1]"},{"function_call":"compter(3)","expected_output":"[1, 2, 3]"},{"function_call":"compter(0)","expected_output":"[]"}]', 'function'),

(4, 4, 'FizzBuzz simplifié', 'Créez une fonction "fizzbuzz" qui prend un nombre n et retourne:\n- "Fizz" si n est divisible par 3\n- "Buzz" si n est divisible par 5\n- "FizzBuzz" si n est divisible par 3 et 5\n- Le nombre lui-même (comme chaîne) sinon\n\nExemple:\nfizzbuzz(15) # retourne "FizzBuzz"',
'[{"function_call":"fizzbuzz(3)","expected_output":"Fizz"},{"function_call":"fizzbuzz(5)","expected_output":"Buzz"},{"function_call":"fizzbuzz(15)","expected_output":"FizzBuzz"},{"function_call":"fizzbuzz(7)","expected_output":"7"}]', 'function'),

(5, 4, 'Fibonacci', 'Créez une fonction "fibonacci" qui retourne le n-ième nombre de la suite de Fibonacci.\n\nExemple:\nfibonacci(6) # retourne 8 (suite: 1,1,2,3,5,8)',
'[{"function_call":"fibonacci(1)","expected_output":"1"},{"function_call":"fibonacci(2)","expected_output":"1"},{"function_call":"fibonacci(6)","expected_output":"8"},{"function_call":"fibonacci(10)","expected_output":"55"}]', 'function');

-- MODULE 5: Manipulation de Chaînes
INSERT INTO `exercices` (`id_exercice`, `id_module`, `titre`, `enonce`, `test_cases`, `type_exercice`) VALUES
(1, 5, 'Longueur de chaîne', 'Créez une fonction "longueur_chaine" qui retourne le nombre de caractères dans une chaîne.\n\nExemple:\nlongueur_chaine("Julia") # retourne 5',
'[{"function_call":"longueur_chaine(\\"Julia\\")","expected_output":"5"},{"function_call":"longueur_chaine(\\"\\")","expected_output":"0"},{"function_call":"longueur_chaine(\\"Hello World\\")","expected_output":"11"},{"function_call":"longueur_chaine(\\"a\\")","expected_output":"1"}]', 'function'),

(2, 5, 'Inverser chaîne', 'Créez une fonction "inverser_chaine" qui retourne une chaîne avec ses caractères inversés.\n\nExemple:\ninverser_chaine("Julia") # retourne "ailuJ"',
'[{"function_call":"inverser_chaine(\\"Julia\\")","expected_output":"ailuJ"},{"function_call":"inverser_chaine(\\"abc\\")","expected_output":"cba"},{"function_call":"inverser_chaine(\\"a\\")","expected_output":"a"},{"function_call":"inverser_chaine(\\"12345\\")","expected_output":"54321"}]', 'function'),

(3, 5, 'Compter voyelles', 'Créez une fonction "compter_voyelles" qui compte le nombre de voyelles (a,e,i,o,u) dans une chaîne.\n\nExemple:\ncompter_voyelles("Julia") # retourne 3',
'[{"function_call":"compter_voyelles(\\"Julia\\")","expected_output":"3"},{"function_call":"compter_voyelles(\\"xyz\\")","expected_output":"0"},{"function_call":"compter_voyelles(\\"aeiou\\")","expected_output":"5"},{"function_call":"compter_voyelles(\\"Hello World\\")","expected_output":"3"}]', 'function'),

(4, 5, 'Palindrome', 'Créez une fonction "est_palindrome" qui vérifie si une chaîne est un palindrome (se lit de la même façon dans les deux sens). Retourne "true" ou "false".\n\nExemple:\nest_palindrome("radar") # retourne "true"',
'[{"function_call":"est_palindrome(\\"radar\\")","expected_output":"true"},{"function_call":"est_palindrome(\\"julia\\")","expected_output":"false"},{"function_call":"est_palindrome(\\"noon\\")","expected_output":"true"},{"function_call":"est_palindrome(\\"a\\")","expected_output":"true"}]', 'function'),

(5, 5, 'Capitaliser mots', 'Créez une fonction "capitaliser" qui met en majuscule la première lettre de chaque mot.\n\nExemple:\ncapitaliser("hello world") # retourne "Hello World"',
'[{"function_call":"capitaliser(\\"hello world\\")","expected_output":"Hello World"},{"function_call":"capitaliser(\\"julia programming\\")","expected_output":"Julia Programming"},{"function_call":"capitaliser(\\"test\\")","expected_output":"Test"},{"function_call":"capitaliser(\\"a b c\\")","expected_output":"A B C"}]', 'function');

-- Ajout d'utilisateurs de test (optionnel)
INSERT INTO `users` (`pseudo`, `email`, `password_hash`) VALUES
('test_user', 'test@example.com', '$2y$10$sHzbne9.rWPjABPBYVP5C.Yl7aUN28rM92p4xmT0E4Gd5m/B54I/.');