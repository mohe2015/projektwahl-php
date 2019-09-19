<?php
/*
projektwahl-php - manage project selection for many people
Copyright (C) 2019 Moritz Hedtke <Moritz.Hedtke@t-online.de>

projektwahl-php is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

projektwahl-php is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with projektwahl-php.  If not, see <https://www.gnu.org/licenses/>.
*/
$users = Users::all();
?>
<form method="post">

<div class="form-group">
  <label class="col">Titel*:</label>
  <input autofocus class="col" type="text" name="title" value="<?php echo htmlspecialchars($project->title) ?>" />
</div>

<div class="form-group">
  <label class="col">Info*:</label>
  <textarea class="col" name="info"><?php echo htmlspecialchars($project->info) ?></textarea>
</div>

<div class="form-group">
  <label class="col">Ich benötige:</label>
  <textarea class="col" name="requirements"><?php echo htmlspecialchars($project->requirements) ?></textarea>
</div>

<div class="form-group">
  <label class="col">Präsentationsart:</label>
  <input class="col" type="text" name="presentation_type" value="<?php echo htmlspecialchars($project->presentation_type) ?>" />
</div>

<div class="form-group">
  <label class="col">Ort/Raum*:</label>
  <input class="col" type="text" name="place" value="<?php echo htmlspecialchars($project->place) ?>" />
</div>

<div class="form-group">
  <label class="col">Kosten:</label>
  <input class="col" type="number" name="costs" value="<?php echo htmlspecialchars($project->costs) ?>" />
</div>

<div class="form-group">
  <label class="col">Jahrgangsstufe*:</label>
  <div class="col">
    <input type="number" name="min_grade" value="<?php echo htmlspecialchars($project->min_grade) ?>" />
    <span>bis</span>
    <input type="number" name="max_grade" value="<?php echo htmlspecialchars($project->max_grade) ?>" />
  </div>
</div>

<div class="form-group">
  <label class="col">Teilnehmeranzahl*:</label>
  <div class="col">
    <input type="number" name="min_participants" value="<?php echo htmlspecialchars($project->min_participants) ?>" />
    <span>bis</span>
    <input type="number" name="max_participants" value="<?php echo htmlspecialchars($project->max_participants) ?>" />
  </div>
</div>

<?php
$project_leaders = array_filter($project_with_project_leaders_and_members, function ($user) {
  return $user->project_leader != NULL;
});
$project_leaders = array_map(function($project_leader) {
    return $project_leader->name;
}, $project_leaders);
?>

<div class="form-group">
  <label class="col">Betreuer:</label>

  <select class="col" id="select-supervisors" name="supervisors[]" multiple>
  <?php
    foreach ($users as $user): ?>
      <option<?php echo in_array($user->name, $project_leaders) ? " selected" : "" ?> class="supervisor-<?php echo htmlspecialchars($user->id) ?>" value="<?php echo htmlspecialchars($user->id) ?>"><?php echo htmlspecialchars($user->name) ?></option>
    <?php endforeach ?>
  </select>

  <button id="show-supervisors-dialog" style="display: none;">
    <?php
    if (count($project_leaders) === 0) {
      echo "Keine";
    } else {
      echo htmlspecialchars(join(', ', $project_leaders));
    }
    ?>
  </button>
  <dialog id="dialog-supervisors">
    <h1>Betreuer</h1>
    <input class="w-100" type="text" placeholder="Suche" id="search-supervisors">
    <ul class="dropdown">
<?php
foreach ($users as $user): ?>
      <li>
        <input type="checkbox" value="" id="supervisor-<?php echo htmlspecialchars($user->id) ?>" <?php echo in_array($user->name, $project_leaders) ? " checked" : " "?>>
        <label for="supervisor-<?php echo htmlspecialchars($user->id) ?>">
          <?php echo htmlspecialchars($user->name) ?>
        </label>
      </li>
<?php endforeach ?>
    </ul>
    <button id="save-supervisors">Schließen</button>
  </dialog>
</div>

<div class="form-group">
  <label>
    <input type="checkbox" name="random_assignments" <?php echo (!empty($project->random_assignments)) ? "checked" : "" ?>>
    Zufällige Projektzuweisungen erlaubt
  </label>
</div>

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

<div class="form-group">
  <button type="submit" class="w-100">Projekt speichern</button>
</div>

</form>

<script src="<?php echo $ROOT ?>/js/projects-form.js"></script>
