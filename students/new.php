<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $student = new Student($_POST); // TODO check if this can modify the type
  try {
    $student->save();
  } catch (Exception $e) {
    die(htmlspecialchars($e->getMessage()));
  }
  header("Location: /students");
  die();
} else {
  $student = new Student(array());
}
?>

<h1>Schüler erstellen</h1>
<?php
require_once __DIR__ . 'form.php';
?>
