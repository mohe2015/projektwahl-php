<?php
$allowed_users = array("admin", "teacher"); // TODO teachers only absent
require_once __DIR__ . '/../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
$student = Students::find($_SERVER['QUERY_STRING']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $student->update($_POST);
    $student->save();
  } catch (Exception $e) {
    die($e->getMessage());
  }
  header("Location: /students");
  die();
}
?>

<h1>Schüler ändern</h1>
<?php
require_once 'form.php';
?>
