<?php
require_once __DIR__ . '/../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $project = new Project($_POST);
  try {
    $project->save();
  } catch (Exception $e) {
    die($e->getMessage());
  }
  header("Location: /projects");
  die();
}
?>

<h1>Projekt erstellen</h1>
<?php
require_once 'form.php';
?>
