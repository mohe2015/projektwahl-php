<?php
$allowed_users = array("admin", "teacher"); // TODO teachers only absent
require_once __DIR__ . '/../head.php';

$student = Students::find($_SERVER['QUERY_STRING']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $_POST['away'] = !empty($_POST['away']);
    $_POST['password'] = empty($_POST['password']) ? NULL : $_POST['password'];
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
