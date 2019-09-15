<?php
$allowed_users = array("admin", "student", "teacher");
require_once __DIR__ . '/head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  session_regenerate_id(true);
  array_pop($_SESSION['users']);
  header("Location: /");
  die();
}
?>
<br>
<form method="post">
  Möchtest Du dich wirklich abmelden?
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
  <button type="submit">Ja</button>
</form>
