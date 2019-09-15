<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

$teacher = Teachers::find($_SERVER['QUERY_STRING']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $_POST['password'] = empty($_POST['password']) ? NULL : $_POST['password'];
    $teacher->update($_POST);
    $teacher->save();
  } catch (Exception $e) {
    die(htmlspecialchars($e->getMessage()));
  }
  header("Location: /teachers");
  die();
}
?>

<h1>Lehrer ändern</h1>
<?php
require_once 'form.php';
?>
