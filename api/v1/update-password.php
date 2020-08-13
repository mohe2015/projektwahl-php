<?php
/*
Diese Software kann eine Projektwahl verwalten, wie sie beispielsweise für eine Projektwoche benötigt wird. 
Copyright (C) 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published
by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
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
    $user->password = password_hash($new_password, PASSWORD_ARGON2ID, $options);
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