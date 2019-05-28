<?php
$allowed_users = array("admin", "teacher"); // TODO only teacher supervisors
require_once __DIR__ . '/../head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $project = new Project($_POST);
  $project->id = $_SERVER['QUERY_STRING'];
  try {
    $project->save();
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
