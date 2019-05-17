<?php
require_once '../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
if (!empty($_POST)) {
  $project = new Project($_POST);
  $project->id = $_SERVER['QUERY_STRING'];
  try {
    Projects::save($project);
  } catch (Exception $e) {
    die($e->getMessage());
  }
  header("Location: /projects");
  die();
} else {
  $project = Projects::find($_SERVER['QUERY_STRING']);
}
?>

<h1>Projekt ändern</h1>
<?php
require_once 'form.php';
?>
