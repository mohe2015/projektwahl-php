<?php
$allowed_users = array();
require_once __DIR__ . '/head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $password = $_POST['password'];
  $user = Users::findByName($name);
  if (password_verify($password, $user->password)) {
    if (password_needs_rehash($user->password, PASSWORD_DEFAULT, $options)) {
      // TODO: needs rehashing
    }
    session_regenerate_id(true);
    $_SESSION['users'][] = $user;
    if ($user->type === "student") {
      header("Location: /election.php");
    } else {
      header("Location: /");
    }
    die();
  } else {
    echo "invalid password";
  }
}
?>
<form method="post">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" autofocus />
  <label for="password">Passwort:</label>
  <input type="password" id="password" name="password" />
  <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
  <button type="submit">Anmelden</button>
</form>
