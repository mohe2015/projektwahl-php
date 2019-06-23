<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $student = new Student();
  $student->id = $_SERVER['QUERY_STRING'];
  try {
    $student->delete();
  } catch (Exception $e) {
    die(htmlspecialchars($e->getMessage()));
  }
  header("Location: /students");
  die();
} else {
  $student = Students::find($_SERVER['QUERY_STRING']);
}
?>

<h1>Schüler löschen</h1>

<form method="post">

<p>Möchten Sie den Schüler <?php echo htmlspecialchars($student->name) ?> wirklich löschen?</p>

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

<div class="form-group">
  <button type="submit" class="w-100">Schüler löschen</button>
</div>

</form>
