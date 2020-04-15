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
$allowed_users = array("student", "teacher", "admin");
require_once __DIR__ . '/head.php';

$user = end($_SESSION['users']); // TODO this needs to be updated from database

if (!$settings->election_running && $user->type !== 'admin') {
  require_once __DIR__ . '/head.php';
  if ($user->in_project !== NULL) {
    // TODO highlight
    echo('<div class="alert alert-info" role="alert">Die Wahl ist beendet! Du bist' . ($user->in_project == $user->project_leader ? ' Projektleiter' : '') . ' im Projekt ' . htmlspecialchars(Projects::find($user->in_project)->title) . '.</div>');
  } else {
    echo('<div class="alert alert-info" role="alert">Die Wahl ist beendet!</div>');
  }
}
?>
<h1>Willkommen</h1>

<h2>Credits</h2>

<p>Diese Software wurde von Moritz Hedtke entwickelt.</p>

<p>Der Quellcode ist aus Transparenzgründen unter <a target="_blank" rel="noopener noreferrer" href="https://github.com/mohe2015/projektwahl-php">https://github.com/mohe2015/projektwahl-php</a> einzusehen.</p>
