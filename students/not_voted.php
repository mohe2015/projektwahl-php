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

$stmt = $db->prepare("SELECT * FROM users WHERE type = 'student' AND away = FALSE ORDER BY class,name;");
$stmt->execute();
$assoc_students = $stmt->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_CLASS, 'Student');

$choices = Choices::allWithUsers();

$grouped_choices = Choices::groupChoices($choices);

$assoc_students = Choices::validateChoices($grouped_choices, $assoc_students);
?>
<!doctype html>
<html lang="de">
  <head>
    <?php require __DIR__ . '/../head.php' ?>
  </head>
  <body class="bg-dark text-white">
    <?php require __DIR__ . '/../nav.php' ?>

    <div class="container">

<h1 class="text-center">Schüler, die noch nicht gewählt haben</h1>

<div class="responsive">
  <table class="table table-dark">
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Klasse</th>
        </tr>
      </thead>
      <tbody>
      <?php
      foreach ($grouped_choices as $student_id => $student_choices) {
        $student = $assoc_students[$student_id];
        if (!$student->valid): ?>
          <tr>
            <td><?php echo htmlspecialchars($student->name) . ($student->project_leader ? " (vmtl. Projektleiter)" : "") ?></td>
            <td><?php echo htmlspecialchars($student->class) ?></td>
          </tr>
        <?php endif;
      } ?>
      </tbody>
  </table>
</div>


<?php require __DIR__ . '/../footer.php' ?>
</body>
</html>
