<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
$students = Students::all();
?>

<h1>Schüler, die noch nicht gewählt haben</h1>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Klasse</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $student) :?>
          <tr>
            <td><?php echo htmlspecialchars($student->name) ?></td>
            <td><?php echo htmlspecialchars($student->class) ?></td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
