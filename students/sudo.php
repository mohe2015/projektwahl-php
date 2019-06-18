<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

$user = Users::find($_SERVER['QUERY_STRING']);

session_regenerate_id(true);
$_SESSION['users'][] = $user;
if ($user->type === "student") {
  header("Location: /election.php");
} else {
  header("Location: /");
}
die();

?>
