<?php
$allowed_users = array("admin");
require_once __DIR__ . '/head.php';

$settings = Settings::get();

if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
<?php
  $settings->election_running = !$settings->election_running;
  $settings->save();
  header("Location: /");
  die();
?>
<?php else: ?>
<br>
<form method="post">
  Möchtest du die Wahl wirklich <?php echo $settings->election_running ? "beenden" : "starten" ?>
  <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
  <button type="submit">Ja</button>
</form>
<?php endif; ?>
