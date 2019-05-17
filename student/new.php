<?php
require_once '../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
if (!empty($_POST)) {
  $student = new Student($_POST); // TODO check if this can modify the type
  try {
    $student->password = "none"; // FIXME
    $student->save();
  } catch (Exception $e) {
    die($e->getMessage());
  }
  header("Location: /students");
  die();
}
?>

<h1>Schüler erstellen</h1>
<?php
require_once 'form.php';
?>
