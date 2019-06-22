<?php
$allowed_users = array("admin", "teacher"); // TODO only teacher supervisors
require_once __DIR__ . '/../head.php';

$project_with_project_leaders_and_members = Projects::findWithProjectLeadersAndMembers($_SERVER['QUERY_STRING']);
$project = $project_with_project_leaders_and_members[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $_POST['random_assignments'] = !empty($_POST['random_assignments']);
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
