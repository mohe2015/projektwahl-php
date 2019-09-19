<?php
$allowed_users = array("admin", "teacher"); // FIXME add teacher as supervisor
require_once __DIR__ . '/../head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $project = new Project($_POST);
  try {
    $project->save();
    header("Location: $ROOT/projects");
    die();
  } catch (Exception $e) {
    echo (htmlspecialchars($e->getMessage()));
  }
} else {
  $project = new Project(array());
}
?>

<h1>Projekt erstellen</h1>
<?php
$project_with_project_leaders_and_members = array();
require_once __DIR__ . '/form.php';
?>
