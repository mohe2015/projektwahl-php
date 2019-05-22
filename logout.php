<?php
require_once __DIR__ . '/head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  session_destroy();
  header("Location: /");
  die();
}
?>
<br>
<form method="post">
  Möchtest Du dich wirklich abmelden?
  <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
  <button type="submit">Ja</button>
</form>
