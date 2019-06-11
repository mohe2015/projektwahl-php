<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/head.php';
?>

<h1>Statistik</h1>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cytoscape/3.7.1/cytoscape.min.js" integrity="sha256-L/QqCCV+29/QBDhoDxOz7wTv5x0PNY90vNk9s3Kur+E=" crossorigin="anonymous"></script>

<style>
#cy {
  width: 100%;
  height: 300px;
  display: block;
}
</style>

<div id="cy"></div>

<script>
fetch('/graph.php')
.then(function(response) {
  return response.json();
})
.then(function(data) {
    var cy = cytoscape({

      container: document.getElementById('cy'), // container to render in

      elements: data,

      style: [ // the stylesheet for the graph
        {
          selector: 'node',
          style: {
            'background-color': '#666',
            'label': 'data(id)'
          }
        },

        {
          selector: 'edge',
          style: {
            'width': 3,
            'line-color': '#ccc',
            'target-arrow-color': '#ccc',
            'target-arrow-shape': 'triangle'
          }
        }
      ]
    });
});
</script>
