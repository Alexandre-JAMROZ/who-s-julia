/**
 * Arbre des modules Julia avec D3.js
 * Génère un arbre interactif pour la sélection des niveaux
 */

// Configuration des données des modules
const treeData = {
  name: "Les Bases",
  description: "Découvrez les bases de Julia et commencez votre quête",
  level: 1,
  status: "available",
  url: "/public/modules/bases/bases.html",
  children: [
    {
      name: "Nombres",
      description: "Maîtrisez les nombres et les opérations de base",
      level: 2,
      status: "locked",
      url: "/public/modules/nombres/nombres.html"
    },
    {
      name: "Structures",
      description: "Explorez les structures de données",
      level: 3,
      status: "locked",
      url: null // À définir plus tard
    }
  ]
};

// Configuration de l'arbre
const config = {
  margin: { top: 50, right: 75, bottom: 50, left: 75 },
  nodeWidth: 300,
  nodeHeight: 80,
  animationDuration: 300,
  animationDelay: 50
};

/**
 * Initialise l'arbre D3.js
 */
function initializeTree() {
  // Calcul des dimensions
  const containerWidth = document.getElementById('tree-container').clientWidth;
  const containerHeight = 350;
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
    .separation((a, b) => a.parent == b.parent ? 1 : 0.8);

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
      .y(d => d.y));
}

/**
 * Crée les nœuds de l'arbre
 */
function createNodes(g, root) {
  const node = g.selectAll(".node")
    .data(root.descendants())
    .enter()
    .append("g")
    .attr("class", d => `node level-${d.data.level}`)
    .attr("transform", d => `translate(${d.x},${d.y})`)
    .style("cursor", d => d.data.url ? "pointer" : "default")
    .on("click", handleNodeClick);

  // Rectangles des nœuds
  node.append("rect")
    .attr("width", config.nodeWidth)
    .attr("height", config.nodeHeight)
    .attr("x", -config.nodeWidth / 2)
    .attr("y", -config.nodeHeight / 2);

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
}

/**
 * Crée les badges de statut pour les nœuds
 */
function createStatusBadges(node) {
  // Cercles des badges
  node.append("circle")
    .attr("class", d => `status-badge ${d.data.status === 'locked' ? 'locked' : ''}`)
    .attr("cx", config.nodeWidth / 2 - 12)
    .attr("cy", -config.nodeHeight / 2 + 8)
    .attr("r", 12);

  // Texte des badges
  node.append("text")
    .attr("class", "badge-text")
    .attr("x", config.nodeWidth / 2 - 12)
    .attr("y", -config.nodeHeight / 2 + 8)
    .text(d => d.data.level);
}

/**
 * Gère les clics sur les nœuds
 */
function handleNodeClick(event, d) {
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

//Point d'entrée: Initialise l'arbre quand le DOM est prêt
document.addEventListener('DOMContentLoaded', function() {
  //délai pour s'assurer que les styles CSS sont chargés
  setTimeout(initializeTree, 100);
});

// Export pour utilisation dans d'autres modules
if (typeof module !== 'undefined' && module.exports) {
  module.exports = { initializeTree, treeData };
}