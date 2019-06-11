<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/head.php';
?>

<h1>Statistik</h1>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" integrity="sha256-iq5ygGJ7021Pi7H5S+QAUXCPUfaBzfqeplbg/KlEssg=" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js" integrity="sha256-JuQeAGbk9rG/EoRMixuy5X8syzICcvB0dj3KindZkY0=" crossorigin="anonymous"></script>

<style type="text/css">
#mynetwork {
  width: 600px;
  height: 400px;
  border: 1px solid lightgray;
}
</style>

<div id="mynetwork"></div>

<script type="text/javascript">
var container = document.getElementById('mynetwork');

fetch('/graph.php')
  .then(function(response) {
    return response.json();
  })
  .then(function(data) {
    var parsed = vis.network.gephiParser.parseGephi(data, {
       fixed: false
     });

     var data = {
       nodes: parsed.nodes,
       edged: parsed.edges
     };

    var network = new vis.Network(container, data, {
      layout: {
        improvedLayout: false
      }
    });
  });

</script>
