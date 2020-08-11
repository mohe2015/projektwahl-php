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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $password = $_POST['password'];
  $user = Users::findByName($name);
  if (!$user) {
    $invalid_username = true;
  } else if (password_verify($password, $user->password)) {
    if (password_needs_rehash($user->password, PASSWORD_ARGON2ID, $options)) {
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
    $invalid_password = true;
  }
} else {
  $invalid_password = false;
  $invalid_username = false;
}
?>