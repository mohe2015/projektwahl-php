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

error_log(print_r($_COOKIE["id"], true), 0);



if (isset($allowed_users) && $allowed_users == false) { // array empty but set - everybody is allowed to access this
  // Do nothing
} else if (!isset($_COOKIE["id"])) { // allowed user not empty, not logged in
  die (json_encode(array(
    "redirect" => "/login", 
    "redirect_back" => true,
    "alert" => "Nicht angemeldet!"
  )));
} else {
  $id = hex2bin($_COOKIE["id"]);
  $session = Sessions::find($id);

  // keep in mind that closing the browser should also end the session
  // but maybe a keep me logged in should be added
  if ($session->created_at + 6 * 60 * 60 < time()) { // 6 hours max lifetime
    die (json_encode(array(
      "redirect" => "/login", 
      "redirect_back" => true,
      "alert" => "Bitte erneut anmelden!"
    )));
  }
  if ($session->updated_at + 60 * 60 < time()) { // 1 hour idle lifetime
    die (json_encode(array(
      "redirect" => "/login", 
      "redirect_back" => true,
      "alert" => "Bitte erneut anmelden!"
    )));
  }
  if ($session->updated_at + 5 * 60 < time()) { // after five minutes update updated_at
    $session->updated_at = time();
    $session->save();
  }

  $user = UserSessions::getCurrent($id);

  if (!in_array($user->type, $allowed_users)) {
     die (json_encode(array('alert' => "Keine Berechtigung!")));
  }
}
?>
