<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bases - Julia</title>
    <link rel="stylesheet" href="/static/css/styles-doc.css">
    <link rel="icon" type="image/x-icon" href="/static/img/favicon.ico">
</head>
<body>
    <div class="layout">
        <?php include __DIR__ . "/../../../config/navDocs.php" ?>

        <main>
            <div class="main">
                <h1>À propos des notions de bases</h1>
                <h2>Commentaires</h2>
                <p>
                    Inclure des commentaires dans votre code est extrêmement important pour aider les humains à lire votre code et à comprendre vos intentions : peut-être d'autres personnes, mais aussi votre avenir. Ils sont (pour la plupart) ignorés par le compilateur.
                </p>
                <p>Deux options sont possibles dans Julia :</p>
                <ul>
                    <li>Les commentaires sur une seule ligne commencent par #</li>
                    <li>Les commentaires multilignes commencent par #= et se termine par =#. La nidification est autorisée.</li>
                </ul>

                <pre><code>
# Ceci est un commentaire sur une seule ligne

x = 3  # Ceci est un commentaire à côté d'une commande

#=
	Les commentaires de plusieurs lignes sont utilisés pour
    des explications plus longues.

	Ils sont utiles pour commenter des blocs pour débogger.
=#                    
                </code></pre>
            </div>

            <div class="minor">
                <h2>Variables et affectation</h2>
                <p>Pour créer une variable, il suffit de lui attribuer une valeur :</p>
                <pre><code>
<span class="prompt">julia></span> reponsedelavie = <span class="value">42</span>
42

<span class="prompt">julia></span> bigint = <span class="value">1_234_567_890</span>
1234567890

<span class="prompt">julia></span> variable = <span class="value">"Julia"</span>
"Julia"

<span class="prompt">julia></span> variable = <span class="value">687</span>
687
                </code></pre>

                <p>
                    Contrairement à de nombreux langages fonctionnels, les variables de Julia peuvent être réaffectées et vous êtes libre de modifier les deux valeur et le type lié à la variable.
                </p>
            </div>

            <div class="minor">
                <h2>Constantes</h2>
                <p>Si une valeur doit être disponible tout au long du programme, mais ne devrait pas changer, utilisez un constante plutôt.</p>
                <p>Le mot-clé <span class="code">const</span> permet au compilateur de générer un code plus efficace.</p>
                <p>Essayer accidentellement de changer la valeur d'un <span class="code">const</span> donnera un avertissement :</p>
                <pre><code>
<span class="prompt">julia></span> const constante = <span class="value">"Julia"</span>
"Julia"

<span class="prompt">julia></span> constante = <span class="value">"Xavier"</span>
WARNING: redefinition of constant Main.answer. This may fail, cause incorrect answers, 
or produce other errors.
"Xavier"
                </code></pre>
            </div>
            <div class="minor">
                <h2>Les Opérateurs Arithmétiques</h2>
                <p>
                    Les opérateurs arithmétiques fonctionnent pour la plupart de la même 
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
            </div>

            <div class="minor">
                <h2>Fonctions</h2>
                <p>
                    Pour de meilleures performances d'exécution, il est préférable de placer la majeure partie du code à l'intérieur fonctions. Avoir beaucoup de petites fonctions, c'est bien, contrairement à certains autres langages.
                </p>
                <p>Il existe deux manières courantes de définir une fonction nommée dans Julia :</p>
                <ol>
                    <li>
                        Utilisation du <span class="code">function</span>
                        <pre><code>
function muladd(x, y, z)
    x * y + z
end
                        </code></pre>
                    </li>

                    <li>
                        Utilisation du "assignement form"
                        <pre><code>
muladd(x, y, z) = x * y + z
                        </code></pre>
                    </li>
                </ol>

                <p>
                    L'invocation d'une fonction se fait en spécifiant son nom et en passant des arguments pour chacun des paramètres de la fonction :
                </p>
                <pre><code>
res = muladd(10, 5, 1)
# résultat est 51
                </code></pre>
            </div>
        </main>
    </div>
</body>
</html>