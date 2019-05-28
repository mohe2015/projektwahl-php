<?php
$allowed_users = array("student");
require_once __DIR__ . '/head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  echo $_POST['project_id'];
  echo $_POST['choice_id'];
}
$projects = Projects::all();
?>

<h1>Projekte</h1>

<a href="/projects/new.php" class="button">Neues Projekt<a>
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
            <td><a href="/projects/view.php?<?php echo $project->id ?>"><?php echo htmlspecialchars($project->title) ?></a></td>
            <td>unbekannt</td>
            <td>
              <?php
              for ($i = 1; $i <= 5; $i++):
              ?>
              <form class="choice-form" method="post" style="display: inline;">
                <input type="hidden" name="project_id" value="<?php echo $project->id ?>">
                <input type="hidden" name="choice_id" value="<?php echo $i ?>">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                <button type="submit"><?php echo $i ?>.</button>
              </form>
              <?php
              endfor;
              ?>
              <form class="choice-form" method="post" style="display: inline;">
                <input type="hidden" name="project_id" value="<?php echo $project->id ?>">
                <input type="hidden" name="choice_id" value="0">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                <button type="submit">X</button>
              </form>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
<script>
function onChoiceSubmit(event) {
  event.preventDefault();
  this.querySelector('button[type="submit"]').setAttribute('disabled', 'disabled');

  fetch("/election.php", {
    method: 'POST',
    body: new FormData(this)
  }).then(function (data) {
    console.log(data);
  });

  return false;
}

document.querySelectorAll(".choice-form").forEach(e => e.addEventListener("submit", onChoiceSubmit));
</script>
