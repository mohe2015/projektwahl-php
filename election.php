<?php
$allowed_users = array("student");
require_once __DIR__ . '/head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $choice = new Choice(array(
    'project' => $_POST['project_id'],
    'student' => $_SESSION['id'],
    'rank' => $_POST['choice_id'],
  ));
  $choice->save();
}
$projects = Projects::all();
?>

<h1>Wahl</h1>

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
            <td><a href="/projects/view.php?<?php echo $project->id ?>"><?php echo htmlspecialchars($project->title) ?></a></td>
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

  this.parentNode.querySelectorAll('button[type="submit"]').forEach(e => e.setAttribute('disabled', null));

  fetch("/election.php", {
    method: 'POST',
    body: new FormData(this)
  }).then((data) => {
    console.log(data);
    this.parentNode.querySelectorAll('button[type="submit"]').forEach(e => e.removeAttribute('disabled'));
  });

  return false;
}

document.querySelectorAll(".choice-form").forEach(e => e.addEventListener("submit", onChoiceSubmit));
</script>
