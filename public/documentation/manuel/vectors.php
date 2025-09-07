<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vecteurs - Julia</title>
    <link rel="stylesheet" href="/static/css/styles-doc.css">
</head>
<body>
    <div class="layout">
        <?php include __DIR__ . "/../../../config/navDocs.php" ?>

        <main>
            <div class="main">
                <h1>À propos des vecteurs</h1>
                <p>
                    L’un des objectifs centraux de Julia est de pouvoir effectuer des calculs sur des données numériques, rapidement et efficacement, sur beaucoup de données numériques.
                </p>
                <p>
                    Certains types de tableaux ont des noms spéciaux. 
                    Un tableau unidimensionnel est appelé un 
                    <span class="code">Vector</span> 
                    et un tableau à 2 dimensions est appelé une 
                    <span class="code">Matrix</span>. 
                    Les deux sont des sous-types de <span class="code">Array</span> 
                    dans Julia.
                </p>
                <h2>Création de vecteurs</h2>
                <pre><code>
julia> num_vec = [1, 4, 9]
3-element Vector{Int64}:
 1
 4
 9

julia> str_vec = ["arrays", "are", "important"]
3-element Vector{String}:
 "arrays"
 "are"
 "important"

julia> mixed_vector = [1, "str", 'c']
3-element Vector{Any}:
 1
  "str"
  'c': ASCII/Unicode U+0063 (category Ll: Letter, lowercase)
                </code></pre>
            </div>

            <div class="minor">
                <h2>Vecteurs préremplis</h2>
                <p>
                    Il est très courant de partir de vecteurs de valeurs all-0 
                    ou all-1. Pour ceux-ci, il existe des fonctions appelées 
                    (sans surprise) <span class="code">zeros()</span> et 
                    <span class="code">ones()</span>, qui prennent la taille 
                    du vecteur comme paramètre. Le type par défaut est Float64, 
                    mais d'autres types numériques peuvent éventuellement être spécifiés.
                </p>
                <p>
                    Par extension, le <span class="code">fill()</span> 
                    la fonction prend à la fois une valeur 
                    à répéter et la taille de vecteur souhaitée.  
                </p>
                <pre><code>
julia> zeros(3)
3-element Vector{Float64}:
 0.0
 0.0
 0.0

julia> ones(3)
3-element Vector{Float64}:
 1.0
 1.0
 1.0

julia> ones(Int64, 3)
3-element Vector{Int64}:
 1
 1
 1

julia> fill(42, 3)
3-element Vector{Int64}:
 42
 42
 42                    
                </code></pre>
            </div>

            <div class="minor">
                <h2>Indexation</h2>
                <p>Par défaut, l'indexation dans Julia commence à 1 et non à 0.</p>
                <p>
                    Comme d'autres langages, pour accéder à une valeur d'un vecteur,
                    il faut mettre l'index entre crochets :
                </p>
                <pre><code>
squares = [0, 1, 4, 9, 16]
squares[1]  # 0
squares[3]  # 4
squares[begin]  # 0 ("begin" est un synonyme de 1)
squares[end]  # 16 ("end" est un synonyme de length(squares))     
                </code></pre>
            </div>
            
            <div class="minor">
                <h2>Opérations vectorielles</h2>
                <p>
                    En Julia, les vecteurs sont <em>mutables</em>, nous pouvons 
                    modifier le contenu des cellules individuelles.
                </p>
                <pre><code>
julia> vals = [1, 3, 5, 7]
4-element Vector{Int64}:
 1
 3
 5
 7

julia> vals[2] = 4  # on change la valeur au deuxième index
4

# Seulement la valeur au deuxième index est changée
julia> vals
4-element Vector{Int64}:
 1
 4
 5
 7                    
                </code></pre>
                <p>
                    Par convention, si une fonction mute son entrée, alors on met 
                    <span class="code">!</span> dans le nom de la fonction.
                </p>
            </div>

            <div class="minor">
                <h2>Quelques fonctions utiles</h2>
                <ul>
                    <li>
                        Pour ajouter des valeurs à la fin du vecteur, utilisez 
                        <span class="code">push!()</span>.
                    </li>
                    <li>
                        Pour supprimer la dernière valeur, utilisez 
                        <span class="code">pop!()</span>.
                    </li>
                    <li>
                        Pour opérer au début du vecteur, les fonctions correspondantes
                        sont <span class="code">pushfirst!()</span> et 
                        <span class="code">popfirst!()</span>.
                    </li>
                    <li>
                        Pour insérer ou retirer un élément à n'importe quelle position,
                        il y a <span class="code">insert!()</span> et 
                        <span class="code">deleteat!()</span>.
                    </li>
                    <li>
                        Pour ajouter plusieurs vecteurs entre eux, utilisez 
                        <span class="code">append!()</span>.
                    </li>
                </ul>
                <pre><code>
julia> vals = [1, 3]
2-element Vector{Int64}:
 1
 3

julia> push!(vals, 5, 6)  # on peut ajouter plusieurs valeurs
4-element Vector{Int64}:
 1
 3
 5
 6

julia> pop!(vals)  # mute le vecteur, retourne la valeur pop
6

julia> vals
3-element Vector{Int64}:
 1
 3
 5

julia> append!([1, 2], [3, 4], [-1, -2], 15)
7-element Vector{Int64}:
  1
  2
  3
  4
 -1
 -2
 15
                </code></pre>
            </div>
        </main>
    </div>
</body>
</html>