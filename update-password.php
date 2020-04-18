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
require_once __DIR__ . '/header.php';

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

$password = '';
?>
<!doctype html>
<html lang="de">
  <head>
    <?php require __DIR__ . '/head.php' ?>
  </head>
  <body class="bg-dark text-white">
    <?php require __DIR__ . '/nav.php' ?>

    <div class="container container-small">

      <form method="post" id="change-password-form">

        <input style="display: none;" type="text" name="username" value="<?php echo $user->name ?>">

        <label class="form-label" for="password">altes Passwort:</label>
        <input class="form-control" autocomplete="current-password" required type="password" id="old_password" name="old_password" value="<?php echo $_SESSION['old_password'] ?? ""; unset($_SESSION['old_password']); ?>" />

        <label class="form-label" for="password">neues Passwort:</label>
        <input class="form-control" autocomplete="new-password" required type="password" id="new_password" name="new_password" value="<?php echo $password ?>" />

        <label class="form-label" for="password">neues Passwort wiederholen:</label>
        <input class="form-control" autocomplete="new-password" required type="password" id="new_password_repeated" name="new_password_repeated" value="<?php echo $password ?>" />

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

        <button type="submit" class="btn btn-primary">Passwort ändern</button>
      </form>

    </div>

    <?php require __DIR__ . '/footer.php' ?>
  </body>
</html>
