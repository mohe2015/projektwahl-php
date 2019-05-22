<?php
require_once '../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
$teachers = Teachers::all();
?>

<h1>Lehrer</h1>

<a href="/teacher/new.php" class="button">Neuer Lehrer<a>
<a href="/teachers/import.php" class="button">Lehrer importieren<a>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($teachers as $teacher) :?>
          <tr>
            <td><?php echo htmlspecialchars($teacher->name) ?></td>
            <td>
              <a href="/teacher/edit.php?<?php echo $teacher->id ?>"><i class="fas fa-pen"></i></a>
              <a href="/teacher/delete.php?<?php echo $teacher->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
