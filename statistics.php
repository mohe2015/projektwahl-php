<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/head.php';
?>

<h1>Statistik</h1>

<style type="text/css">
  #container {
    height: 600px;
    margin: 0;
  }
</style>

<div id="container"></div>
<script src="/sigma.js/build/sigma.min.js"></script>
<script src="/sigma.js/build/plugins/sigma.parsers.json.min.js"></script>
<script src="/sigma.js/build/plugins/sigma.layout.forceAtlas2.min.js"></script>
<script>

var g = {
    nodes: [],
    edges: []
};

var s = new sigma({
  graph: g,
  container: 'container',
  renderer: {
    container: document.getElementById('container'),
    type: 'canvas'
  },
  settings: {
    defaultNodeColor: '#ec5148'
  }
});

sigma.parsers.json('graph.php', s,
        function() {
            // this below adds x, y attributes as well as size = degree of the node
            var i,
                    nodes = s.graph.nodes(),
                    len = nodes.length;

            for (i = 0; i < len; i++) {
                nodes[i].x = Math.random();
                nodes[i].y = Math.random();
            }

            // Refresh the display:
            s.refresh();

            // ForceAtlas Layout
            s.startForceAtlas2({
              linLogMode: true,
              startingIterations: 1000,
              iterationsPerRender: 10,
              scalingRatio: 5,
              edgeWeightInfluence: 1
            });
        });

</script>
