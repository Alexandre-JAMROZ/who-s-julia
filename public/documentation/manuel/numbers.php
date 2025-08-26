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
                <p></p>

                <h2>Les Entiers</h2>
                <p></p>
                <pre><code>
<span class="prompt">julia></span> x = <span class="value">3</span>
3

<span class="prompt">julia></span> typeof(x)
Int64

<span class="prompt">julia></span> grand_nombre = <span class="value">123_456_789</span>
123456789
                </code></pre>
                <p></p>

                <h2>Les Rééls</h2>
                <p></p>
                <pre><code>
<span class="prompt">julia></span> f = <span class="value">3.45</span>
3.45

<span class="prompt">julia></span> typeof(f)
Float64

<span class="prompt">julia></span> avogadro = <span class="value">6.02e23</span>    <span class="comment"># Écriture scientifique</span>
6.02e23                
                </code></pre>
                <p></p>

            </div>

            <div class="minor">
                <h2>Les Opérations Arithmétiques</h2>
                <p></p>
                <pre><code>
<span class="value">2</span> + <span class="value">3</span>     <span class="comment"># 5 (addition)</span>
<span class="value">2</span> - <span class="value">3</span>     <span class="comment"># -1 (soustraction)</span>
<span class="value">2</span> * <span class="value">3</span>     <span class="comment"># 6 (multiplication)</span>
<span class="value">8</span> / <span class="value">2</span>     <span class="comment"># 4.0 (division)</span>
<span class="value">8</span> % <span class="value">3</span>     <span class="comment"># 2 (reste)</span>
<span class="value">2</span> ^ <span class="value">3</span>     <span class="comment"># 8 (exposant)</span>
                </code></pre>
                <p></p>

                <h2>La Multiplication</h2>
                <p></p>
                <pre><code></code></pre>
                <p></p>

                <h2>La Division</h2>
                <p></p>
                <pre><code></code></pre>
                <p></p>
            </div>
        </main>
    </div>
</body>
</html>