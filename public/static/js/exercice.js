// Initialisation de CodeMirror
const editor = CodeMirror.fromTextArea(document.getElementById('code-editor'), {
    lineNumbers: true,
    mode: 'julia',
    theme: 'monokai',
    indentUnit: 4,
    lineWrapping: true
});

// Changement d'exercice
function changeExercise(exerciseId) {
    window.location.href = `exercice.php?module=${moduleId}&ex=${exerciseId}`;
}

// Effacer le code
function clearCode() {
    if (confirm('Êtes-vous sûr de vouloir effacer tout le code ?')) {
        editor.setValue('');
    }
}

// Validation et exécution combinées
function validateCode() {
    const code = editor.getValue();
    const outputDiv = document.getElementById('output');
    const feedbackDiv = document.getElementById('feedback');

    outputDiv.innerHTML = '<div class="loading">Exécution et validation en cours...</div>';
    feedbackDiv.innerHTML = '';

    fetch('execute_julia.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            code: code,
            module_id: moduleId,
            exercise_id: currentExercise
        })
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur réseau');
        return response.json();
    })
    .then(data => {
        // Sortie du programme
        if (data.output) {
            outputDiv.innerHTML = `<pre>${data.output}</pre>`;
        } else if (data.has_error === false && !data.output) {
            outputDiv.innerHTML = '<div class="info">Fonction définie avec succès (aucune sortie)</div>';
        } else {
            outputDiv.innerHTML = '<div class="info">Aucune sortie</div>';
        }

        if (data.success) {
            feedbackDiv.innerHTML = `
                <div class="success-message">
                    <h3>🎉 Félicitations !</h3>
                    <p>${data.message}</p>
                    ${data.validation_details ? `
                        <div class="test-results">
                            <p>Tests réussis : ${data.validation_details.passed_count}/${data.validation_details.total_count}</p>
                        </div>
                    ` : ''}
                    ${data.warning ? `<p class="warning">${data.warning}</p>` : ''}
                    ${data.next_exercise ?
                        `<button onclick="window.location.href='exercice.php?module=${moduleId}&ex=${data.next_exercise}'" class="btn-primary">Exercice suivant →</button>` :
                        `<p>Vous avez terminé tous les exercices de ce module !</p>
                        <button onclick="window.location.href='../../modules/modules.php'" class="btn-primary">Retour aux modules</button>`
                    }
                </div>
            `;
            document.querySelector('.progress-text').textContent =
                `Progression : ${data.completed}/${data.total}`;
            document.querySelector('.progress-fill').style.width =
                `${(data.completed / data.total) * 100}%`;
        } else {
            let testDetails = '';
            if (data.validation_details?.test_results?.length > 0) {
                testDetails = '<div class="test-details">';
                testDetails += `<p>Tests réussis : ${data.validation_details.passed_count}/${data.validation_details.total_count}</p>`;
                testDetails += '<ul>';
                data.validation_details.test_results.forEach(test => {
                    if (!test.passed) {
                        testDetails += `<li class="failed-test">
                            Test ${test.test_number} : ❌<br>
                            ${test.input ? `Entrée : ${test.input}<br>` : ''}
                            Attendu : "${test.expected}"<br>
                            Obtenu : "${test.actual || 'Erreur'}"
                            ${test.error ? `<br>Erreur : ${test.error}` : ''}
                        </li>`;
                    } else {
                        testDetails += `<li class="passed-test">Test ${test.test_number} : ✅</li>`;
                    }
                });
                testDetails += '</ul></div>';
            }

            const errorMessage = (data.validation_details && data.validation_details.has_error)
                ? 'Erreur dans le code. Vérifiez la sortie ci-dessus.'
                : data.message;

            feedbackDiv.innerHTML = `
                <div class="error-message">
                    <h3>❌ Pas encore...</h3>
                    <p>${errorMessage}</p>
                    ${testDetails}
                    ${!data.validation_details || !data.validation_details.has_error ?
                        '<p class="hint">💡 Indice : Vérifiez que votre code produit exactement le résultat attendu.</p>' : ''}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        outputDiv.innerHTML = '';
        feedbackDiv.innerHTML = `<div class="error-message">Erreur de connexion au serveur.</div>`;
    });
}

// Sauvegarde automatique
let saveTimeout;
editor.on('change', function() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        fetch('save_code.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                code: editor.getValue(),
                module_id: moduleId,
                exercise_id: currentExercise
            })
        });
    }, 1000);
});
