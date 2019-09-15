<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/head.php';
?>

<h1>Statistik</h1>

<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.9.2/d3.min.js"></script>
<script src="http://marvl.infotech.monash.edu/webcola/cola.v3.min.js"></script>

<svg id="graph" viewBox="0,0,600,600">

</svg>
<!-- TODO https://ialab.it.monash.edu/webcola/ -->
<script src="/js/statistics.js"></script>
