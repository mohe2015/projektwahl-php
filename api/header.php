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

session_set_cookie_params(array(
  "lifetime" => 6 * 60 * 60, // 6 hours
  "path" => "/",
  "secure" => true,
  "httponly" => true,
  "samesite" => "Strict",
));

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/config.php';
try {
    $db = new PDO($database['url'], $database['username'], $database['password'], array(
//    PDO::ATTR_PERSISTENT => true, // doesn't work with sqlite
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ));
} catch (PDOException $e) {
  die (json_encode(array('alert' => "Datenbankfehler: " . strval($e))));
}

require_once __DIR__ . '/record.php';
?>
