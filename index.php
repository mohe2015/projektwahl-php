<?php
require_once 'head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
?>
<h1>Willkommen</h1>

<h2>Credits</h2>

<p>Diese Software wurde von Moritz Hedtke entwickelt.</p>

<p>Der Quellcode ist aus Transparenzgründen unter <a target="_blank" rel="noopener noreferrer" href="https://github.com/mohe2015/projektwahl-php">https://github.com/mohe2015/projektwahl-php</a> einzusehen.</p>
