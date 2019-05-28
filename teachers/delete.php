<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $teacher = new Teacher();
  $teacher->id = $_SERVER['QUERY_STRING'];
  try {
    $teacher->delete();
  } catch (Exception $e) {
    die($e->getMessage());
  }
  header("Location: /teachers");
  die();
} else {
  $teacher = Teachers::find($_SERVER['QUERY_STRING']);
}
?>

<h1>Lehrer löschen</h1>

<form method="post">

<p>Möchten Sie den Lehrer <?php echo htmlspecialchars($teacher->name) ?> wirklich löschen?</p>

<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />

<div class="form-group">
  <button type="submit" class="w-100">Lehrer löschen</button>
</div>

</form>
