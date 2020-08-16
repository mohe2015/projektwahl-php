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

$allowed_users = array("admin", "project-manager", "user");
require_once __DIR__ . '/../header.php';

$old_password = $_POST['old-password'];
$new_password = $_POST['new-password'];
$new_password_repeated = $_POST['new-password-repeated'];

if ($new_password !== $new_password_repeated) {
  die (json_encode(array('errors' => array(
    "new-password-repeated" => "Passwörter nicht gleich!",
  ))));
} else if (password_verify($old_password, $user->password_hash)) {
  $user->password_hash = password_hash($new_password, PASSWORD_ARGON2ID, $options);
  $user->password_changed = true;
  $user->save();

  // TODO FIXME change session_id
  // TODO FIXME logout all other sessions of this user
  
  if ($user->type === "student") {
    die (json_encode(array('redirect' => "/election")));
  } else {
    die (json_encode(array('redirect' => "/")));
  }
} else {
  die (json_encode(array('errors' => array(
    "old-password" => "Altes Passwort ist falsch!",
  ))));
}