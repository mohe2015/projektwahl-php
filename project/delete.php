<?php
require_once '../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $project = new Project();
  $project->id = $_SERVER['QUERY_STRING'];
  try {
    $project->delete();
  } catch (Exception $e) {
    die($e->getMessage());
  }
  header("Location: /projects");
  die();
} else {
  $project = Projects::find($_SERVER['QUERY_STRING']);
}
?>

<h1>Projekt löschen</h1>

<form method="post">

<p>Möchten Sie das Projekt <?php echo htmlspecialchars($project->title) ?> wirklich löschen?</p>

<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />

<div class="form-group">
  <button type="submit" class="w-100">Projekt löschen</button>
</div>

</form>