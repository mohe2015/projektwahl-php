<?php
$allowed_users = array("admin", "teacher", "student"); // TODO fixme not all atributes
require_once __DIR__ . '/../head.php';

$project = Projects::find($_SERVER['QUERY_STRING']);
?>

<?php
require_once '../project/project.php';
?>
