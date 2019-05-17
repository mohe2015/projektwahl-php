<?php
require_once '../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
if (!empty($_POST)) {
  $project = new Project($_POST);
  try {
    Projects::save($project);
  } catch (Exception $e) {
    print $e->getMessage();
  }
}
?>

<h1>Projekt erstellen</h1>
<?php
require_once 'form.php';
?>
