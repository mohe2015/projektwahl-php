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
$allowed_users = array("admin");
require_once __DIR__ . '/head.php';

$settings = Settings::get();

if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
<?php
  $settings->election_running = !$settings->election_running;
  $settings->save();
  header("Location: $ROOT/");
  die();
?>
<?php else: ?>
<br>
<form method="post">
  Möchtest du die Wahl wirklich <?php echo $settings->election_running ? "beenden" : "starten" ?>
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
  <button type="submit">Ja</button>
</form>
<?php endif; ?>
