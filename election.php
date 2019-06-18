<?php
$allowed_users = array("student");
require_once __DIR__ . '/head.php';

$settings = Settings::get();
if (!$settings->election_running) {
  die("Die Wahl ist beendet!"); // TODO make more beautiful
}

$user = end($_SESSION['users']);

// save an updated choice
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $choice = new Choice(array(
    'project' => $_POST['project_id'],
    'student' => $user->id,
    'rank' => $_POST['choice_id'],
  ));
  $choice->save();
}

// TODO only load the list if this is not a fetch post request
$projects = Projects::allWithRanks();
?>

<h1>Wahl</h1>

<p>Bitte wähle Deine Erst- bis Fünftwahl aus.</p>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Projektname</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rank_count = array(
          1 => 0,
          2 => 0,
          3 => 0,
          4 => 0,
          5 => 0
        );
        foreach ($projects as $project) {
          if ($project->rank == NULL) {
            continue;
          }
          $rank_count[$project->rank]++;
        }
        foreach ($projects as $project): ?>
          <tr data-rank="<?php echo $project->rank ?>">
            <td><a href="/projects/view.php?<?php echo $project->id ?>"><?php echo htmlspecialchars($project->title) ?></a></td>
            <td class="nowrap right">
                <?php if ($project->min_grade > $user->grade): ?>
                  zu jung
                <?php elseif ($project->max_grade < $user->grade): ?>
                  zu alt
                <?php else: ?>
                <?php
                // the following html form code can fall back for browsers without JavaScript.
                for ($i = 1; $i <= 5; $i++):
                ?>
                <form class="choice-form" method="post" style="display: inline;">
                  <input type="hidden" name="project_id" value="<?php echo $project->id ?>">
                  <input type="hidden" name="choice_id" value="<?php echo $i ?>">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                  <button class="<?php echo $project->rank != 0 ? ($rank_count[$project->rank] == 1 ? "background-success" : "background-failure") : "" ?>" data-rank="<?php echo $i ?>" type="submit" <?php echo $project->rank == $i ? "disabled=disabled" : "" ?>><?php echo $i ?>.</button>
                </form>
                <?php
                endfor;
                ?>
                <form class="choice-form" method="post" style="display: inline;">
                  <input type="hidden" name="project_id" value="<?php echo $project->id ?>">
                  <input type="hidden" name="choice_id" value="0">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                  <button class="<?php echo $project->rank != 0 ? ($rank_count[$project->rank] == 1 ? "background-success" : "background-failure") : "" ?>" data-rank="0" type="submit" <?php echo $project->rank == 0 ? "disabled=disabled" : "" ?>>X</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>

<div class='scrolltop'>
    <div class='scroll icon' id="scroll">Sortieren</div>
</div>

</div>

<?php
if ($rank_count[1] == 1 && $rank_count[2] == 1 && $rank_count[3] == 1 && $rank_count[4] == 1 && $rank_count[5] == 1):
?>
<div id="snackbar" class="snackbar show background-success">
  Gültig gewählt - Du kannst Dich nun <a href="/logout.php">abmelden</a>
</div>
<?php
else:
?>
<div id="snackbar" class="snackbar show">
  <span class=\"failure\">Ungültig gewählt</span> -
  <span class="<?php echo $rank_count[1] == 1 ? "success" : "failure" ?>"><?php echo $rank_count[1]; ?>&times;1.</span> |
  <span class="<?php echo $rank_count[2] == 1 ? "success" : "failure" ?>"><?php echo $rank_count[2]; ?>&times;2.</span> |
  <span class="<?php echo $rank_count[3] == 1 ? "success" : "failure" ?>"><?php echo $rank_count[3]; ?>&times;3.</span> |
  <span class="<?php echo $rank_count[4] == 1 ? "success" : "failure" ?>"><?php echo $rank_count[4]; ?>&times;4.</span> |
  <span class="<?php echo $rank_count[5] == 1 ? "success" : "failure" ?>"><?php echo $rank_count[5]; ?>&times;5.</span>
</div>
<?php
endif;
?>

<!-- This is a polyfill to support the old firefox browser in the school. -->
<script crossorigin="anonymous" src="https://polyfill.io/v3/polyfill.min.js?flags=gated&features=NodeList.prototype.forEach"></script>
<script src="voting.js"></script>
