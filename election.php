<?php
$allowed_users = array("student");
require_once __DIR__ . '/head.php';

// save an updated choice
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
              // the following html form code can fall back for browsers without JavaScript.
              $rank = Choices::find($_SESSION['id'], $project->id)->rank; // TODO natural join
              for ($i = 1; $i <= 5; $i++):
              ?>
              <form class="choice-form" method="post" style="display: inline;">
                <input type="hidden" name="project_id" value="<?php echo $project->id ?>">
                <input type="hidden" name="choice_id" value="<?php echo $i ?>">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                <button data-rank="<?php echo $i ?>" type="submit" <?php echo $rank == $i ? "disabled=disabled" : "" ?>><?php echo $i ?>.</button>
              </form>
              <?php
              endfor;
              ?>
              <form class="choice-form" method="post" style="display: inline;">
                <input type="hidden" name="project_id" value="<?php echo $project->id ?>">
                <input type="hidden" name="choice_id" value="0">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                <button data-rank="0" type="submit" <?php echo $rank == 0 ? "disabled=disabled" : "" ?>>X</button>
              </form>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
<!-- This is a polyfill to support the old firefox browser in the school. -->
<script crossorigin="anonymous" src="https://polyfill.io/v3/polyfill.min.js?flags=gated&features=NodeList.prototype.forEach"></script>
<script src="voting.js"></script>
