<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

$teacher = Teachers::find($_SERVER['QUERY_STRING']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $teacher->update($_POST);
    $teacher->save();
  } catch (Exception $e) {
    die($e->getMessage());
  }
  header("Location: /teachers");
  die();
}
?>

<h1>Lehrer ändern</h1>
<?php
require_once 'form.php';
?>
