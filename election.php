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
              <div class="radio-group">
                <input type="radio" id="option-one" name="selector"><label for="option-one">1.</label><input type="radio" id="option-two" name="selector"><label for="option-two">2.</label><input type="radio" id="option-three" name="selector"><label for="option-three">3.</label><input type="radio" id="option-four" name="selector"><label for="option-four">4.</label><input type="radio" id="option-five" name="selector"><label for="option-five">5.</label><input type="radio" id="option-sixth" name="selector"><label for="option-sixth">X</label>
              </div>
              <!--  <label class="">
                  <input type="radio" name="projects[<?php echo $project->id ?>][choice]" value="1" autocomplete="off"> 1.
                </label>
                <label class="">
                  <input type="radio" name="projects[<?php echo $project->id ?>][choice]" value="2" autocomplete="off"> 2.
                </label>
                <label class="">
                  <input type="radio" name="projects[<?php echo $project->id ?>][choice]" value="3" autocomplete="off"> 3.
                </label>
                <label class="">
                  <input type="radio" name="projects[<?php echo $project->id ?>][choice]" value="4" autocomplete="off"> 4.
                </label>
                <label class="">
                  <input type="radio" name="projects[<?php echo $project->id ?>][choice]" value="5" autocomplete="off"> 5.
                </label>
                <label class="">
                  <input type="radio" name="projects[<?php echo $project->id ?>][choice]" value="0" autocomplete="off"> X
                </label>-->
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
