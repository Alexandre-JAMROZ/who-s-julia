/**
 * Arbre des modules Julia avec D3.js
 * Génère un arbre pour la sélection des niveaux
 * Version mise à jour pour intégration avec le système d'exercices PHP
 */

// Configuration des données des modules - Correspond à la base de données
const treeData = {
  name: "Les Bases",
  description: "Découvrez les bases de Julia et commencez votre quête",
  level: 1,
  status: "available",
  url: "exercice/exercice.php?module=1",
  moduleId: 1,
  children: [
    {
      name: "Fonctions et Calculs",
      description: "Maîtrisez les fonctions et les opérations mathématiques",
      level: 2,
      status: "locked",
      url: "exercice/exercice.php?module=2",
      moduleId: 2,
      children: [
        {
          name: "Conditions et Boucles",
          description: "Contrôlez le flux de votre programme",
          level: 4,
          status: "locked",
          url: "exercice/exercice.php?module=4",
          moduleId: 4
        }
      ]
    },
    {
      name: "Structures de Données",
      description: "Explorez les structures de données",
      level: 3,
      status: "locked",
      url: "exercice/exercice.php?module=3",
      moduleId: 3,
      children: [
        {
          name: "Manipulation de Chaînes",
          description: "Travaillez avec les chaînes de caractères",
          level: 5,
          status: "locked",
          url: "exercice/exercice.php?module=5",
          moduleId: 5
        }
      ]
    }
  ]
};


// Configuration de l'arbre
const config = {
  margin: { top: 50, right: 100, bottom: 50, left: 100 },
  nodeWidth: 320,
  nodeHeight: 70,
  animationDuration: 300,
  animationDelay: 50
};

/**
 * Récupère le statut des modules depuis le serveur
 */
async function fetchModuleStatus() {
  try {
    const response = await fetch('get_module_status.php');
    if (response.ok) {
      const data = await response.json();
      updateTreeStatus(treeData, data);
    }
  } catch (error) {
    console.error('Erreur lors de la récupération du statut des modules:', error);
  }
}

/**
 * Met à jour le statut des modules dans l'arbre
 */
function updateTreeStatus(node, statusData) {
  if (statusData[node.moduleId]) {
    node.status = statusData[node.moduleId].completed ? 'completed' : 
                  statusData[node.moduleId].started ? 'in-progress' : 
                  statusData[node.moduleId].unlocked ? 'available' : 'locked';
  }
  
  if (node.children) {
    node.children.forEach(child => updateTreeStatus(child, statusData));
  }
}

/**
 * Initialise l'arbre D3.js
 */
async function initializeTree() {
  // Récupération du statut des modules
  await fetchModuleStatus();
  
  // Calcul des dimensions
  const containerWidth = document.getElementById('tree-container').clientWidth;
  const containerHeight = 600; // augmenter si les modules dépassent
  const width = containerWidth - config.margin.left - config.margin.right;
  const height = containerHeight - config.margin.top - config.margin.bottom;

  // Création du SVG principal
  const svg = d3.select("#tree-container")
    .append("svg")
    .attr("width", containerWidth)
    .attr("height", containerHeight);

  // Définition des gradients
  createGradients(svg);

  // Groupe principal avec marges
  const g = svg.append("g")
    .attr("transform", `translate(${config.margin.left},${config.margin.top})`);

  // Configuration de l'algorithme d'arbre
  const tree = d3.tree()
    .size([width, height])
    .separation((a, b) => a.parent == b.parent ? 1.2 : 1.5);

  // Transformation des données en hiérarchie
  const root = d3.hierarchy(treeData);
  tree(root);

  // Création des éléments visuels
  createLinks(g, root);
  createNodes(g, root);

  // Configuration du responsive
  setupResponsive(svg, tree, root, g, width, height);

  // Animations d'entrée
  animateEntrance(g);
}

/**
 * Crée les gradients CSS pour les nœuds
 */
function createGradients(svg) {
  const defs = svg.append("defs");
  
  // Gradient pour le niveau principal
  const primaryGradient = defs.append("linearGradient")
    .attr("id", "gradient-primary")
    .attr("gradientTransform", "rotate(135)");
  
  primaryGradient.append("stop")
    .attr("offset", "0%")
    .attr("stop-color", "#3C79F5");
  
  primaryGradient.append("stop")
    .attr("offset", "100%")
    .attr("stop-color", "#5A8DF7");

  // Gradient pour les niveaux secondaires
  const accentGradient = defs.append("linearGradient")
    .attr("id", "gradient-accent")
    .attr("gradientTransform", "rotate(135)");
  
  accentGradient.append("stop")
    .attr("offset", "0%")
    .attr("stop-color", "#FF5449");
  
  accentGradient.append("stop")
    .attr("offset", "100%")
    .attr("stop-color", "#FF6B5F");
  
  // Gradient pour les modules complétés
  const completedGradient = defs.append("linearGradient")
    .attr("id", "gradient-completed")
    .attr("gradientTransform", "rotate(135)");
  
  completedGradient.append("stop")
    .attr("offset", "0%")
    .attr("stop-color", "#4CAF50");
  
  completedGradient.append("stop")
    .attr("offset", "100%")
    .attr("stop-color", "#66BB6A");
}

/**
 * Crée les liens entre les nœuds
 */
function createLinks(g, root) {
  const link = g.selectAll(".link")
    .data(root.links())
    .enter()
    .append("path")
    .attr("class", "link")
    .attr("d", d3.linkVertical()
      .x(d => d.x)
      .y(d => d.y))
    .style("fill", "none")
    .style("stroke", "#ccc")
    .style("stroke-width", 2);
}

/**
 * Crée les nœuds de l'arbre
 */
function createNodes(g, root) {
  const node = g.selectAll(".node")
    .data(root.descendants())
    .enter()
    .append("g")
    .attr("class", d => `node level-${d.data.level} status-${d.data.status}`)
    .attr("transform", d => `translate(${d.x},${d.y})`)
    .style("cursor", d => d.data.status !== 'locked' ? "pointer" : "not-allowed")
    .on("click", handleNodeClick);

  // Rectangles des nœuds
  node.append("rect")
    .attr("width", config.nodeWidth)
    .attr("height", config.nodeHeight)
    .attr("x", -config.nodeWidth / 2)
    .attr("y", -config.nodeHeight / 2)
    .attr("fill", d => {
      if (d.data.status === 'completed') return "url(#gradient-completed)";
      if (d.data.status === 'in-progress') return "url(#gradient-accent)";
      if (d.data.status === 'available') return "url(#gradient-primary)";
      return "#ccc";
    });

  // Titres des nœuds
  node.append("text")
    .attr("class", "title")
    .attr("dy", "-8")
    .text(d => d.data.name);

  // Descriptions des nœuds
  node.append("text")
    .attr("class", "description")
    .attr("dy", "12")
    .text(d => d.data.description);

  // Badges de statut
  createStatusBadges(node);
  
  // Icône de cadenas pour les modules verrouillés
  node.filter(d => d.data.status === 'locked')
    .append("text")
    .attr("class", "lock-icon")
    .attr("x", 0)
    .attr("y", 35)
    .attr("text-anchor", "middle")
    .style("font-size", "20px")
    .text("🔒");
}

/**
 * Crée les badges de statut pour les nœuds
 */
function createStatusBadges(node) {
  // Cercles des badges
  node.append("circle")
    .attr("class", d => `status-badge ${d.data.status}`)
    .attr("cx", config.nodeWidth / 2 - 12)
    .attr("cy", -config.nodeHeight / 2 + 8)
    .attr("r", 12)
    .attr("fill", d => {
      if (d.data.status === 'completed') return "#4CAF50";
      if (d.data.status === 'in-progress') return "#FF9800";
      if (d.data.status === 'available') return "#3C79F5";
      return "#9E9E9E";
    });

  // Icônes des badges
  node.append("text")
    .attr("class", "badge-text")
    .attr("x", config.nodeWidth / 2 - 12)
    .attr("y", -config.nodeHeight / 2 + 12)
    .attr("text-anchor", "middle")
    .style("font-size", "16px")
    .text(d => {
      if (d.data.status === 'completed') return "✓";
      if (d.data.status === 'in-progress') return "...";
      return d.data.level;
    });
}

/**
 * Gère les clics sur les nœuds
 */
function handleNodeClick(event, d) {
  // Si le module est verrouillé, afficher un message
  if (d.data.status === 'locked') {
    alert('Ce module sera débloqué après avoir complété les modules précédents.');
    return;
  }
  
  // Si pas d'URL définie, ne rien faire
  if (!d.data.url) {
    return;
  }
  
  // Redirection vers l'URL
  window.location.href = d.data.url;
}

/**
 * Configure le comportement responsive
 */
function setupResponsive(svg, tree, root, g, originalWidth, originalHeight) {
  window.addEventListener('resize', function() {
    const newWidth = document.getElementById('tree-container').clientWidth;
    const newSvgWidth = newWidth;
    const newTreeWidth = newWidth - config.margin.left - config.margin.right;
    
    // Mise à jour des dimensions
    svg.attr("width", newSvgWidth);
    tree.size([newTreeWidth, originalHeight]);
    
    // Recalcul des positions
    tree(root);
    
    // Mise à jour des positions des nœuds
    g.selectAll(".node")
      .attr("transform", d => `translate(${d.x},${d.y})`);
    
    // Mise à jour des liens
    g.selectAll(".link")
      .attr("d", d3.linkVertical()
        .x(d => d.x)
        .y(d => d.y));
  });
}

/**
 * Anime l'entrée des éléments
 */
function animateEntrance(g) {
  // Animation des nœuds
  g.selectAll(".node")
    .style("opacity", 0)
    .transition()
    .duration(config.animationDuration)
    .delay((d, i) => i * config.animationDelay)
    .style("opacity", 1);

  // Animation des liens
  g.selectAll(".link")
    .style("opacity", 0)
    .transition()
    .duration(config.animationDuration)
    .delay(config.animationDelay * 2)
    .style("opacity", 1);
}

// Point d'entrée: Initialise l'arbre quand le DOM est prêt
document.addEventListener('DOMContentLoaded', function() {
  // Délai pour s'assurer que les styles CSS sont chargés
  setTimeout(initializeTree, 100);
});

// Export pour utilisation dans d'autres modules
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { initializeTree, treeData };
}