<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $teacher = new Teacher($_POST); // TODO check if this can modify the type
  try {
    $teacher->password = "none"; // FIXME
    $teacher->save();
  } catch (Exception $e) {
    die($e->getMessage());
  }
  header("Location: /teachers");
  die();
}
?>

<h1>Lehrer erstellen</h1>
<?php
require_once 'form.php';
?>