<?php

$allowed_users = array();
require_once __DIR__ . '/head.php';

$user = end($_SESSION['users']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $old_password = $_POST['old_password'];
  $new_password = $_POST['new_password'];
  $new_password_repeated = $_POST['new_password_repeated'];

  if ($new_password !== $new_password_repeated) {
    echo "Passwörter nicht identisch!";
  } else if (password_verify($old_password, $user->password)) {
    $user->password = password_hash($new_password, PASSWORD_DEFAULT, $options);
    $user->password_changed = true;
    $user->save();
    array_pop($_SESSION['users']);
    $_SESSION['users'][] = $user;
    if ($user->type === "student") {
      header("Location: $ROOT/election.php");
    } else {
      header("Location: $ROOT/");
    }
    die();
  } else {
    echo "Altes Passwort ist falsch!";
  }
}

$lines = file("eff_short_wordlist_1.txt", FILE_IGNORE_NEW_LINES);
$password = '';
for ($i = 0; $i < 4; $i++) {
  $password = $password . " " . $lines[random_int(0, count($lines)-1)];
}
$password = trim($password);
?>
<!-- TODO format this form -->
<form method="post">
  <input style="display: none;" autocomplete="username" type="text" name="username" value="<?php echo $user->name ?>">
  <label for="password">altes Passwort:</label>
  <input autocomplete="current-password" required type="password" id="old_password" name="old_password" value="<?php echo $_SESSION['old_password'] ?? ""; unset($_SESSION['old_password']); ?>" /><br>
  <label for="password">neues Passwort:</label>
  <input autocomplete="new-password" required type="text" id="new_password" name="new_password" value="<?php echo $password ?>" /> <button type="button" id="show-password"><i class="fas fa-eye"></i></button><br>

  <meter max="4" id="password-strength-meter"></meter>
  <p id="password-strength-text"></p>

  <label for="password">neues Passwort wiederholen:</label>
  <input autocomplete="new-password" required type="password" id="new_password_repeated" name="new_password_repeated" value="<?php echo $password ?>" /><br>
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
  <button type="submit">Passwort ändern</button>
</form>
<script src="/password.js"></script>
