<?php
$allowed_users = array("admin", "teacher"); // FIXME add teacher as supervisor
require_once __DIR__ . '/../head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $project = new Project($_POST);
  try {
    $project->save();
  } catch (Exception $e) {
    die($e->getMessage());
  }
  header("Location: /projects");
  die();
} else {
  $project = new Project(array());
}
?>

<h1>Projekt erstellen</h1>
<?php
require_once 'form.php';
?>
