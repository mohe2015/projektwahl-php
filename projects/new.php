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
$allowed_users = array("admin", "teacher"); // FIXME add teacher as supervisor
require_once __DIR__ . '/../head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $project = new Project($_POST);
  try {
    $project->save();
    header("Location: $ROOT/projects");
    die();
  } catch (Exception $e) {
    echo (htmlspecialchars($e->getMessage()));
  }
} else {
  $project = new Project(array());
}
?>

<h1 class="text-center">Projekt erstellen</h1>
<?php
$project_with_project_leaders_and_members = array();
require_once __DIR__ . '/form.php';
?>
