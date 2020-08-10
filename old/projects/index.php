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

<h1 class="text-center">Projekte</h1>

<a role="button" class="btn btn-primary mb-1" href="<?php echo $ROOT ?>/projects/new.php">Neues Projekt</a>
<a role="button" class="btn btn-primary mb-1" href="<?php echo $ROOT ?>/projects/list.php">Projektliste</a>

<div class="responsive">
  <table class="table table-dark">
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $project): ?>
          <tr>
            <td><a href="<?php echo $ROOT ?>/projects/view.php?<?php echo $project->id ?>"><?php echo htmlspecialchars($project->title) ?></a></td>
            <td>
              <a href="<?php echo $ROOT ?>/projects/edit.php?<?php echo $project->id ?>"><i class="fas fa-pen"></i></a>
              <a href="<?php echo $ROOT ?>/projects/delete.php?<?php echo $project->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>

</div>
<?php require __DIR__ . '/../footer.php' ?>
</body>
</html>
