<?php
$allowed_users = array("admin", "teacher"); // TODO fixme not all attributes
require_once __DIR__ . '/../head.php';

$student = Students::find($_SERVER['QUERY_STRING']);
?>

<?php
require_once __DIR__ . '/student.php';
?>
