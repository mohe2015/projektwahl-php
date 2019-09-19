<?php
/*
projektwahl-php - manage project selection for many people
Copyright (C) 2019 Moritz Hedtke <Moritz.Hedtke@t-online.de>

projektwahl-php is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

projektwahl-php is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with projektwahl-php.  If not, see <https://www.gnu.org/licenses/>.
*/
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
