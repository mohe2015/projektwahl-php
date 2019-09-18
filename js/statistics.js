height = 600
width = 600
function color() {
  const scale = d3.scaleOrdinal(d3.schemeCategory10);
  return function(d) { return scale(d.group) };
}
drag = function (simulation) {

  function dragstarted(d) {
    if (!d3.event.active) simulation.alphaTarget(0.3).restart();
    d.fx = d.x;
    d.fy = d.y;
  }

  function dragged(d) {
    d.fx = d3.event.x;
    d.fy = d3.event.y;
  }

  function dragended(d) {
    if (!d3.event.active) simulation.alphaTarget(0);
    d.fx = null;
    d.fy = null;
  }

  return d3.drag()
      .on("start", dragstarted)
      .on("drag", dragged)
      .on("end", dragended);
}

async function graph() {
  data = await d3.json("/graph.php")

  const links = data.links.map(function (d) { return Object.create(d) });
  const nodes = data.nodes.map(function (d) { return Object.create(d) });

  const simulation = d3.forceSimulation(nodes)
      .force("link", d3.forceLink(links).id(function (d) { return d.id }))
      .force("charge", d3.forceManyBody())
      .force("center", d3.forceCenter(width / 2, height / 2));

  const svg = d3.select("#graph");

  const link = svg.append("g")
      .attr("stroke", "#999")
      .attr("stroke-opacity", 0.6)
    .selectAll("line")
    .data(links)
    .join("line")
      .attr("stroke-width", function (d) { return Math.sqrt(d.value) });

  const node = svg.append("g")
      .attr("stroke", "#fff")
      .attr("stroke-width", 1.5)
    .selectAll("circle")
    .data(nodes)
    .join("circle")
      .attr("r", 5)
      .attr("fill", color)
      .call(drag(simulation));

  node.append("title")
      .text(function (d) { return d.id });

  simulation.on("tick", function () {
    link
        .attr("x1", function (d) { return d.source.x })
        .attr("y1", function (d) { return d.source.y })
        .attr("x2", function (d) { return d.target.x })
        .attr("y2", function (d) { return d.target.y });

    node
        .attr("cx", function (d) { return d.x })
        .attr("cy", function (d) { return d.y });
  });

//  invalidation.then(function() { simulation.stop() });

  return svg.node();
}
var graph = graph();
