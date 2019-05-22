<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $teacher = new Teacher($_POST);
  $teacher->id = $_SERVER['QUERY_STRING'];
  try {
    $teacher->password = "none"; // FIXME
    $teacher->save();
  } catch (Exception $e) {
    die($e->getMessage());
  }
  header("Location: /teachers");
  die();
} else {
  $teacher = Teachers::find($_SERVER['QUERY_STRING']);
}
?>

<h1>Lehrer ändern</h1>
<?php
require_once 'form.php';
?>
