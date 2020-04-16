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
$allowed_users = array("admin", "teacher"); // TODO only teacher supervisors
require_once __DIR__ . '/../header.php';

$project_with_project_leaders_and_members = Projects::findWithProjectLeadersAndMembers($_SERVER['QUERY_STRING']);
$project = $project_with_project_leaders_and_members[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $_POST['random_assignments'] = !empty($_POST['random_assignments']);
    $project->update($_POST);
    $project->save();
  } catch (Exception $e) {
    die(htmlspecialchars($e->getMessage()));
  }
  header("Location: $ROOT/projects");
  die();
}
?>
<!doctype html>
<html lang="de">
  <head>
    <?php require __DIR__ . '/../head.php' ?>
  </head>
  <body class="bg-dark text-white">
    <?php require __DIR__ . '/../nav.php' ?>

    <div class="container">

<h1>Projekt ändern</h1>
<?php
require_once __DIR__ . '/form.php';
?>

</div>

<?php require __DIR__ . '/../footer.php' ?>
</body>
</html>
