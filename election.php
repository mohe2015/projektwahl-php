<?php
$allowed_users = array("student");
require_once __DIR__ . '/head.php';
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
        <?php foreach ($projects as $project): ?>
          <tr>
            <td><a href="/project/view.php?<?php echo $project->id ?>"><?php echo htmlspecialchars($project->title) ?></a></td>
            <td>unbekannt</td>
            <td>
              <form method="post">
                <input type="hidden" name="project_id" value="<?php echo $project->id ?>">
                <input type="hidden" name="choice_id" value="1">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                <button type="submit">1.</button>
              </form>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
