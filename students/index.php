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
require_once __DIR__ . '/../head.php';

$stmt = $db->prepare("SELECT id, * FROM users WHERE type = 'student' ORDER BY class,name;");
$stmt->execute();
$assoc_students = $stmt->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_CLASS, 'Student');

$choices = Choices::allWithUsersWithAway();

$grouped_choices = Choices::groupChoices($choices);

$assoc_students = Choices::validateChoices($grouped_choices, $assoc_students);
?>

<h1>Schüler</h1>

<a href="<?php echo $ROOT ?>/students/new.php" class="button">Neuer Schüler</a>
<a href="<?php echo $ROOT ?>/students/import.php" class="button">Schüler importieren</a>
<form class="inline-block" method="POST" action="generate_passwords.php">
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
  <button type="submit" href="<?php echo $ROOT ?>/teachers/generate_passwords.php" class="button">Passwortliste generieren</button>
</form>
<a href="<?php echo $ROOT ?>/students/not_voted.php" class="button">Schüler ohne gewählte Projekte</a>
<a href="<?php echo $ROOT ?>/students/calculate.php" class="button">Projektzuordnungen berechnen</a>
<br>

<span style="background-color: green;">Gültig gewählt</span>
<span style="background-color: orange;">Ungültig gewählt</span>
<span style="background-color: red;">Nicht gewählt</span>
<span style="background-color: LightSeaGreen;">vorraussichtlich Projektleiter</span>
<span style="background-color: grey;">Abwesend</span>

<input class="w-100" type="search" id="search" placeholder="Suche nach Name oder Klasse">

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Klasse</th>
          <th scope="col">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($grouped_choices as $student_id => $student_choices):
          $student = $assoc_students[$student_id];
           ?>
          <tr id="<?php echo str_replace(" ", "-", $student->name . " " . $student->class) ?>" style="background-color: <?php echo $student->away ? 'grey' : ($student->project_leader ? 'LightSeaGreen' : ($student->valid ? 'green' : (count($student_choices) > 0 ? 'orange' : 'red'))) ?>;">
            <td><a href="<?php echo $ROOT ?>/students/view.php?<?php echo $student->id ?>"><?php echo htmlspecialchars($student->name) ?></a></td>
            <td><?php echo htmlspecialchars($student->class) ?></td>
            <td>
              <a href="<?php echo $ROOT ?>/students/edit.php?<?php echo $student->id ?>"><i class="fas fa-pen"></i></a>
              <form class="inline-block" method="post" action="edit.php?<?php echo $student->id ?>">
                <input type="hidden" name="away" value="<?php echo $student->away ? "" : "checked" ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                <button class="a" type="submit"><i class="fas <?php echo $student->away ? "fa-user-slash" : "fa-user" ?>"></i></button>
              </form>
              <form class="inline-block" method="post" action="edit.php?<?php echo $student->id ?>">
                <input type="hidden" name="password" value="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                <button class="a" type="submit"><i class="fas fa-key"></i></button>
              </form>
              <form class="inline-block" method="post" action="sudo.php?<?php echo $student->id ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                <button class="a" type="submit"><i class="fas fa-sign-in-alt"></i></button>
              </form>
              <a href="<?php echo $ROOT ?>/students/delete.php?<?php echo $student->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
  </table>
</div>

<script src="<?php echo $ROOT ?>/js/students-search.js"></script>
