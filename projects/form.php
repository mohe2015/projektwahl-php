<?php
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

<div class="form-group">
  <label class="col">Betreuer:</label>

  <select class="col" id="select-supervisors" name="supervisors" multiple>
    <?php
    $project_leaders = array_map(function($project_leader) {
        return $project_leader->name;
    }, $project_with_project_leaders);
    foreach ($users as $user): ?>
      <option<?php echo in_array($user->name, $project_leaders) ? " selected" : "" ?>><?php echo $user->name ?></option>
    <?php endforeach ?>
  </select>

  <button id="show-supervisors-dialog" style="display: none;">
    <?php
    if (count($project_with_project_leaders) === 0) {
      echo "Keine";
    } else {
      echo join(', ', array_map(function($project_leader) {
          return $project_leader->name;
      }, $project_with_project_leaders));
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
        <input type="checkbox" value="" id="<?php echo $user->name ?>" <?php echo in_array($user->name, $project_leaders) ? " checked" : " "?>>
        <label for="<?php echo $user->name ?>">
          <?php echo $user->name ?>
        </label>
      </li>
<?php endforeach ?>
    </ul>
    <menu>
      <button id="save-supervisors">Schließen</button>
    </menu>
  </dialog>
</div>

<div class="form-group">
  <label>
    <input type="checkbox" name="random_assignments" <?php echo (!empty($project->random_assignments)) ? "checked" : "" ?>>
    Zufällige Projektzuweisungen erlaubt
  </label>
</div>

<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />

<div class="form-group">
  <button type="submit" class="w-100">Projekt speichern</button>
</div>

</form>


<script>
var form = $("#form-supervisors");
var dialog = $("#dialog-supervisors");
var button = $('#show-supervisors-dialog');
var input = $('#search-supervisors');
button.style = "";

dialog.addEventListener('close', function onClose(e) {
  console.log(e);
  e.preventDefault();
  $('body').classList.remove('modal-open');
});

$('#save-supervisors').addEventListener('click', function(event) {
  event.preventDefault();
  var supervisors = $$('input:checked').map(x => x.id).join("; ") || "Keine";
  button.innerText = supervisors;
  dialog.close();
});

button.addEventListener('click', function (event) {
  event.preventDefault();
  document.querySelector('body').classList.add('modal-open');
  dialog.show();
});

input.addEventListener('input', function(event) {
  var supervisors = $$('input[type="checkbox"]');
  var query = event.target.value.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
  supervisors.forEach(e => {
    var string = e.id.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
    if (string.toLowerCase().indexOf(query.toLowerCase()) === -1) {
      e.parentElement.hidden = true;
    } else {
      e.parentElement.hidden = false;
    }
  });
  // TODO put selected ones up
  // myElement.innerHTML = null
});

// Hide the other one if javascript loaded
$('#select-supervisors').hidden = true;

</script>
