<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
$students = Students::all();
?>

<h1>Schüler</h1>

<a href="/student/new.php" class="button">Neuer Schüler</a>
<a href="/students/import.php" class="button">Schüler importieren</a>
<a href="/students/not_voted.php" class="button">Schüler ohne gewählte Projekte</a>
<a href="/students/calculate.php" class="button">Projektzuordnungen berechnen</a>

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
        <?php foreach ($students as $student) :?>
          <tr>
            <td><?php echo htmlspecialchars($student->name) ?></td>
            <td><?php echo htmlspecialchars($student->class) ?></td>
            <td>
              <a href="/student/edit.php?<?php echo $student->id ?>"><i class="fas fa-pen"></i></a>
              <a href="/student/delete.php?<?php echo $student->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
