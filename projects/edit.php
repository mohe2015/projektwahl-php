<?php
$allowed_users = array("admin", "teacher"); // TODO only teacher supervisors
require_once __DIR__ . '/../head.php';

$project = Projects::find($_SERVER['QUERY_STRING']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $project->update($_POST);
    $project->save();
  } catch (Exception $e) {
    die($e->getMessage());
  }
  header("Location: /projects");
  die();
}
?>

<h1>Projekt ändern</h1>
<?php
require_once 'form.php';
?>