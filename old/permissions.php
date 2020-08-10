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
if (isset($allowed_users) && $allowed_users == false) { // array empty but set
  // Do nothing
} else if (!isset(end($_SESSION['users'])->name)) {
  header("Location: $ROOT/login.php");
  die("Nicht angemeldet!");
} else if (!in_array(end($_SESSION['users'])->type, $allowed_users)) {
  http_response_code(403);
  die("Keine Berechtigung!");
}
?>