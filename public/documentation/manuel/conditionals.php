<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions - Julia</title>
    <link rel="stylesheet" href="/static/css/styles-doc.css">
    <link rel="icon" type="image/x-icon" href="/static/img/favicon.ico">
</head>
<body>
    <div class="layout">
        <?php include __DIR__ . "/../../../config/navDocs.php" ?>

        <main>
            <div class="main">
                <h1>À propos des conditions</h1>
                <h2>Opérateurs de comparaison</h2>
                <p>
                    Les opérateurs de comparaison dans Julia sont similaires à de nombreux autres 
                    langages, mais avec quelques options supplémentaires pour les amateurs de mathématiques.
                </p>
                <p>
                    Pour l'égalité, les opérateurs sont 
                    <span class="code">==</span> (égal) et 
                    <span class="code">!=</span> ou 
                    <span class="code">≠</span> (pas égal).
                </p>
                <pre><code>
txt = "abc"
txt == "abc"  # true
txt != "abc"  # false
txt ≠ "abc"  # false (synonym for !=)                   
                </code></pre>
                <p>
                    De plus, nous avons les différents opérateurs supérieurs/inférieurs.
                </p>
                <pre><code>
1 < 3  # true
3 > 3  # false
3 <= 3  # true
3 ≤ 3  # true (synonym for <=)
4 >= 3  # true
4 ≥ 3  # true (synonym for >=)

n = 3
1 ≤ n ≤ 5  # true, synonyme de 1 ≤ n && n ≤ 5
                </code></pre>
            </div>

            <div class="minor">
                <h2>Forme d'un bloc conditionnel</h2>
                <p>C'est la forme complète d'un bloc if :</p>
                <pre><code>
if condition1
    code...
elseif condition2
    code...
else
    code...
end
                </code></pre>
                <p>
                    Il n'est pas nécessaire de mettre des parenthèses 
                    <span class="code">()</span> ou des accolades
                    <span class="code">{}</span>, et l'indentation est « uniquement » 
                    destinée à améliorer la lisibilité (mais la lisibilité est très importante !).
                </p>
                <p>
                    Les deux <span class="code">elseif</span> et 
                    <span class="code">else</span> sont facultatifs et il peut y 
                    avoir plusieurs 
                    <span class="code">elseif</span> dans un bloc. 
                    Cependant, le <span class="code">end</span> est requis.
                </p>
                <p>La forme la plus courte d'un if ressemblerait à ceci :</p>
                <pre><code>
if n < 0
    n = 0
end
                </code></pre>
            </div>

            <div class="minor">
                <h2>Opération ternaire</h2>
                <p>Julia, comme beaucoup de langages, dispose d'un opérateur ternaire pour rendre cela plus concis.</p>
                <p>
                    La syntaxe est 
                    <span class="code">condition ? valeur_si_vrai : valeur_si_faux</span>.
                </p>
                <pre><code>
n = n < 0 ? 0 : n
                </code></pre>
            </div>
        </main>
    </div>
</body>
</html>