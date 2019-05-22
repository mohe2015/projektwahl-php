<?php
require_once __DIR__ . '/../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
$project = Projects::find($_SERVER['QUERY_STRING']);
?>

<?php
require_once '../project/project.php';
?>
