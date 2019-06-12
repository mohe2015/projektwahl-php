<?php
$allowed_users = array("admin", "teacher", "student"); // TODO fixme not all atributes
require_once __DIR__ . '/../head.php';

$project_with_project_leaders = Projects::findWithProjectLeaders($_SERVER['QUERY_STRING']);
$project = $project_with_project_leaders[0];
?>

<?php
require_once __DIR__ . '/project.php';
?>
