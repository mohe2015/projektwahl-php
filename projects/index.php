<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
$projects = Projects::all();
?>

<h1>Projekte</h1>

<a href="/project/new.php" class="button">Neues Projekt<a>
<a href="/projects/list.php" class="button">Projektliste<a>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Vorraussichtliche Größe</th>
          <th scope="col">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $project) :?>
          <tr>
            <td><a href="/project/view.php?<?php echo $project->id ?>"><?php echo htmlspecialchars($project->title) ?></a></td>
            <td>unbekannt</td>
            <td>
              <a href="/project/edit.php?<?php echo $project->id ?>"><i class="fas fa-pen"></i></a>
              <a href="/project/delete.php?<?php echo $project->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
