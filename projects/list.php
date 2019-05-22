<?php
require_once __DIR__ . '/../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
$projects = Projects::all();
?>

<h1>Projektliste</h1>

<?php foreach ($projects as $project) :?>
<?php
require_once '../project/project.php';
?>
<?php endforeach;?>
