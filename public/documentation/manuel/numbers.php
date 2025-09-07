<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nombres - Julia</title>
    <link rel="stylesheet" href="/static/css/styles-doc.css">
</head>
<body>
    <div class="layout">
        <?php include __DIR__ . "/../../../config/navDocs.php" ?>

        <main>
            <div class="main">
                <h1>À propos des nombres</h1>
                <p>
                    Julia est un langage à usage général, qui peut être utilisé pour la plupart 
                    des tâches de programmation. Dans la pratique, cependant, les principaux cas 
                    d’utilisation concernent généralement l’ingénierie et les sciences. Des calculs 
                    numériques rapides, polyvalents et sophistiqués sont au cœur de la conception.
                </p>

                <h2>Les Entiers</h2>
                <p>Un entier est un nombre « rond » sans virgule décimale.</p>
                <p>Pour plus de lisibilité, les traits de soulignement peuvent être 
                    utilisés comme séparateur de chiffres. Ils sont ignorés par le compilateur.</p>
                <pre><code>
<span class="prompt">julia></span> x = <span class="value">3</span>
3

<span class="prompt">julia></span> typeof(x)
Int64

<span class="prompt">julia></span> grand_nombre = <span class="value">123_456_789</span>
123456789
                </code></pre>
                <p>
                    En interne, le compilateur utilisera le type entier signé le plus approprié 
                    pour votre processeur. Sur les PC modernes, ce sera généralement le cas 
                    <span class="code">Int64</span>, 
                    ce qui est parfaitement adapté à la plupart des tâches.
                </p>

                <h2>Les Float</h2>
                <p>
                    Un float (réél) est un nombre avec une partie fractionnaire après la 
                    partie décimale.
                </p>
                <p>La notation scientifique est prise en charge.</p>
                <pre><code>
<span class="prompt">julia></span> f = <span class="value">3.45</span>
3.45

<span class="prompt">julia></span> typeof(f)
Float64

<span class="prompt">julia></span> avogadro = <span class="value">6.02e23</span>    <span class="comment"># Écriture scientifique</span>
6.02e23                
                </code></pre>
                <p>
                    Comme pour les entiers, le type par défaut 
                    <span class="code">Float64</span> convient à la plupart des fins, 
                    mais d’autres types signés sont disponibles.
                </p>

            </div>

            <div class="minor">
                <h2>Les Opérations Arithmétiques</h2>
                <p>
                    Les opérations arithmétiques fonctionnent pour la plupart de la même 
                    manière que l’arithmétique standard. Notez que l'exponentiation est utilisée 
                    avec <span class="code">^</span> et pas <span class="code">**</span> comparé 
                    à certains langages.
                </p>
                <pre><code>
<span class="value">2</span> + <span class="value">3</span>     <span class="comment"># 5 (addition)</span>
<span class="value">2</span> - <span class="value">3</span>     <span class="comment"># -1 (soustraction)</span>
<span class="value">2</span> * <span class="value">3</span>     <span class="comment"># 6 (multiplication)</span>
<span class="value">8</span> / <span class="value">2</span>     <span class="comment"># 4.0 (division)</span>
<span class="value">8</span> % <span class="value">3</span>     <span class="comment"># 2 (reste)</span>
<span class="value">2</span> ^ <span class="value">3</span>     <span class="comment"># 8 (exposant)</span>
                </code></pre>
                <p>Cependant, quelques détails spécifiques à Julia méritent d’être discutés.</p>

                <h2>La Multiplication</h2>
                <pre><code>
<span class="prompt">julia></span> x = <span class="value">3.4</span>
3.4

<span class="prompt">julia></span> <span class="value">2</span> * x
6.8

<span class="prompt">julia></span> <span class="value">2</span>x
6.8

<span class="prompt">julia></span> <span class="value">2.2</span>x
7.48

<span class="prompt">julia></span> surface(r) = <span class="value">4</span><span class="language-julia">π</span> * r^<span class="value">2</span>
sruface (generic function with 1 method)

<span class="prompt">julia></span> surface(<span class="value">3</span>)
113.09733552923255
                </code></pre>
                <p>
                    Il est toujours possible d'utiliser <span class="code">*</span>
                    en tant que infixe opérateur, 
                    comme dans la plupart des autres langages informatiques.
                </p>
                <p>
                    Cependant, Julia est conçue par des personnes qui croient que le 
                    code doit ressembler autant que possible à des équations 
                    mathématiques.
                </p>
                <p>
                    Étant donné que les noms de variables doivent commencer par 
                    une lettre, préfacer le nom par un nombre (entier ou à virgule 
                    flottante) est traité comme une multiplication implicite.
                </p>

                <h2>La Division</h2>
                <p>
                    L'utilisation de <span class="code">/</span> comme opérateur 
                    donnera toujours un 
                    résultat à virgule flottante, même pour les entrées entières.
                </p>
                <p>Pour la division entière, il existe plus d'options :</p>
                <pre><code>
<span class="prompt">julia></span> <span class="value">10</span> / <span class="value">3</span>
3.3333333333333335

<span class="prompt">julia></span> div(<span class="value">10</span>, <span class="value">3</span>)
3

<span class="prompt">julia></span> <span class="value">10</span> ÷ <span class="value">3</span>
3

<span class="prompt">julia></span> <span class="value">22</span> // <span class="value">6</span>
11//3
                </code></pre>
                <p>
                    La fonction <span class="code">div()</span> est pour la 
                    division entière, avec le résultat tronqué vers zéro : vers le 
                    bas pour les nombres positifs, vers le haut pour les nombres négatifs.
                </p>
                <p>
                    Comme synonyme, nous pouvons utiliser l'opérateur infixe 
                    <span class="code">÷</span>, encore une fois dans le but de le 
                    rendre plus mathématique. Si vous utilisez un éditeur 
                    compatible Julia, saisissez 
                    <span class="code">\div</span> puis appuyez sur la touche 
                    <span class="code">Tab</span>.
                </p>
                <p>
                    L'opérateur <span class="code">//</span> aura besoin d'un 
                    concept qui lui soit propre, plus tard dans le programme.
                    Pour l'instant, on peut simplement dire que le résultat de 
                    <span class="code">//</span> est un nombre « rationnel », 
                    le nom officiel de ce que la plupart des gens appellent un fraction.
                </p>
            </div>
        </main>
    </div>
</body>
</html>