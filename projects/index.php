<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../head.php';

$projects = Projects::all();
?>

<h1>Projekte</h1>

<a href="<?php echo $ROOT ?>/projects/new.php" class="button">Neues Projekt<a>
<a href="<?php echo $ROOT ?>/projects/list.php" class="button">Projektliste<a>

<div class="responsive">
  <table>
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
