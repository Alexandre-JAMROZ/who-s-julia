<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booléens - Julia</title>
    <link rel="stylesheet" href="/static/css/styles-doc.css">
    <link rel="icon" type="image/x-icon" href="/static/img/favicon.ico">
</head>
<body>
    <div class="layout">
        <?php include __DIR__ . "/../../../config/navDocs.php" ?>
    
        <main>
            <div class="main">
                <h1>À propos des booléens</h1>
                <h2>Les Booléens en Julia</h2>
                <p>
                    Les valeurs vrai et fausse sont 
                    représentés par le type <strong>Bool</strong>. 
                    Il contient seulement deux valeurs : <strong>True</strong> 
                    et <strong>False</strong>.
                </p>

                <pre><code>
<span class="prompt">julia></span> <span class="language-julia">true</span>
true 

<span class="prompt">julia></span> <span class="language-julia">false</span> 
false 

<span class="prompt">julia></span> typeof(<span class="language-julia">true</span>) 
true
                </code></pre>

                <p>
                    Contrairement à d'autres langages, Julia n'a pas de 
                    concept de "vrai ou faux", seulement les expressions
                    délibérément instanciées <strong>True ou False</strong>
                    sont des booléens.
                </p>
                <p>
                    Par exemple, une liste vide n'est pas considérée <strong>False</strong>, 
                    il faudra créer une fonction comme <span class="code">estVide()</span> pour gérer 
                    ce cas particulier.
                </p>
            </div>

            <div class="minor">
                <h2>Opérateurs booléens</h2>
                <p>
                    Il y a des opérateurs booléens en Julia.
                </p>
                <p>
                    <span class="code">&&</span> est le booléen <strong>"et"</strong>. 
                    Le résultat est <strong>True</strong> si l'expression 
                    de gauche <strong>et</strong> de droite sont aussi <strong>True</strong>.
                </p>

                <pre><code>
<span class="prompt">julia></span> <span class="language-julia">true</span> && <span class="language-julia">true</span>
true 

<span class="prompt">julia></span> <span class="language-julia">true</span> && <span class="language-julia">false</span>
false 
                </code></pre>

                <p>
                    <span class="code">||</span> est le booléen <strong>"ou"</strong>. 
                    Le résultat est <strong>True</strong> si l'expression 
                    de gauche <strong>ou</strong> de droite est <strong>True</strong>.
                </p>

                <pre><code>
<span class="prompt">julia></span> <span class="language-julia">true</span> || <span class="language-julia">true</span>
true 

<span class="prompt">julia></span> <span class="language-julia">true</span> || <span class="language-julia">false</span>
false 
                </code></pre>

                <p>
                    <span class="code">!</span> est le booléen <strong>"contraire"</strong>. 
                    Cela inverse la valeur du booléen.
                </p>

                <pre><code>
<span class="prompt">julia></span> !<span class="language-julia">true</span>
false 

<span class="prompt">julia></span> !<span class="language-julia">false</span>
true 
                </code></pre>

                <p>
                    Ces opérateurs sont familiers à d'autres langages, la syntaxe peut 
                    par contre varier.
                </p>
            </div>
        </main>
    </div>  
</body>
</html>