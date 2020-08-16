<?php
/*
SPDX-FileCopyrightText: 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>
SPDX-License-Identifier: AGPL-3.0-or-later

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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  die();
}

$allowed_users = array();
require_once __DIR__ . '/../header.php';

$username = $_POST['username'];
$password = $_POST['password'];
$user = Users::findByName($username);

if (!$user) {
  die (json_encode(array('errors' => array(
    "username" => "Nutzer nicht gefunden!",
    "password" => "Nutzer nicht gefunden!",
  ))));
} else if (password_verify($password, $user->password_hash)) {
  if (password_needs_rehash($user->password_hash, PASSWORD_ARGON2ID, $options)) {
    $user->password_hash = password_hash($password, PASSWORD_ARGON2ID, $options);
    $user->save();
  }
  
  // TODO create session
  $bytes = random_bytes(32);

  // SELECT strftime('%s', 'now');  returns current unix time
  // DATETIME(0, 'unixepoch');
  // just compare the seconds

  setcookie("id", bin2hex($bytes), array(
    "expires" => time() + 6 * 60 * 60, // 6 hours
    "path" => "/",
    "secure" => true,
    "httponly" => true,
    "samesite" => "Strict",
  ));
  setcookie("username", $username, array(
    "expires" => time() + 6 * 60 * 60, // 6 hours
    "path" => "/",
    "secure" => true,
    "samesite" => "Strict",
  ));

  if (!$user->password_changed) {
    die (json_encode(array('custom' => true, 'redirect' => "/update-password")));
  } else if ($user->type === "student") {
    die (json_encode(array('redirect' => "/election")));
  } else {
    die (json_encode(array('redirect' => "/")));
  }
} else {
  die (json_encode(array('errors' => array(
    "password" => "Passwort falsch!",
  ))));
}

?>