<?php
/*
projektwahl-php - manage project selection for many people
Copyright (C) 2019 Moritz Hedtke <Moritz.Hedtke@t-online.de>

projektwahl-php is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

projektwahl-php is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with projektwahl-php.  If not, see <https://www.gnu.org/licenses/>.
*/
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
    if (!$user->password_changed) {
      $_SESSION['old_password'] = $password;
      header("Location: $ROOT/update-password.php");
    } else if ($user->type === "student") {
      header("Location: $ROOT/election.php");
    } else {
      header("Location: $ROOT/");
    }
    die();
  } else {
    echo '<div class="alert alert-danger" role="alert">Passwort falsch!</div>';
  }
}
?>
<form method="post">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" autofocus />
  <label for="password">Passwort:</label>
  <input type="password" id="password" name="password" />
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
  <button type="submit">Anmelden</button>
</form>
