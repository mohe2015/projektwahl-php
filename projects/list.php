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
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../header.php';

$projects = Projects::all();
?>
<!doctype html>
<html lang="de">
  <head>
    <?php require __DIR__ . '/../head.php' ?>
  </head>
  <body class="bg-dark text-white">
    <?php require __DIR__ . '/../nav.php' ?>

    <div class="container">

<h1 class="d-print-none">Projektliste</h1>

<!-- TODO remove space + remove some info from the list or put some things on one line to not waste space -->

<?php foreach ($projects as $project):
$project_with_project_leaders_and_members = Projects::findWithProjectLeadersAndMembers($project->id); // TODO FIXME this is an N + 1 query which is really bad
?>
<?php
require __DIR__ . '/project.php';
?>
<?php endforeach;?>

</div>
<?php require __DIR__ . '/../footer.php' ?>
</body>
</html>
