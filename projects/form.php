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

<label>Titel*:</label>
<input autofocus type="text" name="title" value="<?php echo htmlspecialchars($project->title) ?>" />

<label>Info*:</label>
<textarea name="info"><?php echo htmlspecialchars($project->info) ?></textarea>

<label>Ich benötige:</label>
<textarea name="requirements"><?php echo htmlspecialchars($project->requirements) ?></textarea>

<label>Präsentationsart:</label>
<input type="text" name="presentation_type" value="<?php echo htmlspecialchars($project->presentation_type) ?>" />

<label>Ort/Raum*:</label>
<input class="col" type="text" name="place" value="<?php echo htmlspecialchars($project->place) ?>" />

<label>Kosten:</label>
<input type="number" name="costs" value="<?php echo htmlspecialchars($project->costs) ?>" />

<label>Jahrgangsstufe*:</label>
<div class="input-group">
  <input class="form-control" type="number" name="min_grade" value="<?php echo htmlspecialchars($project->min_grade) ?>" />
  <span class="input-group-text">bis</span>
  <input class="form-control" type="number" name="max_grade" value="<?php echo htmlspecialchars($project->max_grade) ?>" />
</div>

<label>Teilnehmeranzahl*:</label>
<div class="input-group">
  <input class="form-control" type="number" name="min_participants" value="<?php echo htmlspecialchars($project->min_participants) ?>" />
  <span class="input-group-text">bis</span>
  <input class="form-control" type="number" name="max_participants" value="<?php echo htmlspecialchars($project->max_participants) ?>" />
</div>

<?php
$project_leaders = array_filter($project_with_project_leaders_and_members, function ($user) {
  return $user->project_leader != NULL;
});
$project_leaders = array_map(function($project_leader) {
    return $project_leader->name;
}, $project_leaders);
?>

<label>Betreuer:</label>

<div>
  <select id="select-supervisors" name="supervisors[]" multiple>
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
  <div class="modal fade" tabindex="-1" role="dialog" id="dialog-supervisors">
    <div class="modal-dialog" role="document">
      <div class="modal-content bg-dark">
          <div class="modal-header">
            <h5 class="modal-title">Betreuer</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input class="w-100" type="text" placeholder="Suche" id="search-supervisors">
            <ul class="dropdown">
            <?php
            foreach ($users as $user): ?>
                <li>
                  <input type="checkbox" value="" id="supervisor-<?php echo htmlspecialchars($user->id) ?>" <?php echo in_array($user->name, $project_leaders) ? " checked" : " "?>>
                  <label for="supervisor-<?php echo htmlspecialchars($user->id) ?>"><?php echo htmlspecialchars($user->name) ?></label>
                </li>
            <?php endforeach ?>
            </ul>
          </div>
          <div class="modal-footer">
            <button id="save-supervisors" data-dismiss="modal">Schließen</button>
          </div>
        </div>
    </div>
  </div>
</div>

<div style="grid-column: span 2;">
  <input id="random_assignments" type="checkbox" name="random_assignments" <?php echo (!empty($project->random_assignments)) ? "checked" : "" ?>>
  <label for="random_assignments">Zufällige Projektzuweisungen erlaubt</label>
</div>

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

<button type="submit">Projekt speichern</button>

</form>

<script src="<?php echo $ROOT ?>/js/projects-form.js"></script>
