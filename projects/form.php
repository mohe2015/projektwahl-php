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
    if (count($project_leaders) === 1 && is_null($project->name)) {
      echo "Keine";
    } else {
      echo htmlspecialchars(join(', ', array_map(function($project_leader) {
          return $project_leader->name;
      }, $project_leaders)));
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
        <label for="<?php echo htmlspecialchars($user->id) ?>">
          <?php echo htmlspecialchars($user->name) ?>
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

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

<div class="form-group">
  <button type="submit" class="w-100">Projekt speichern</button>
</div>

</form>

<script>
var form = $("#form-supervisors");
var dialog = $("#dialog-supervisors");
dialogPolyfill.registerDialog(dialog);
var button = $('#show-supervisors-dialog');
var input = $('#search-supervisors');
button.style = "";

// TODO implement escape
dialog.addEventListener('close', function onClose(e) {
  e.preventDefault();
  $('body').classList.remove('modal-open');
});

$('#save-supervisors').addEventListener('click', function(event) {
  event.preventDefault();
  var supervisors = $$('input:checked').map(x => x.id).join("; ") || "Keine";
  button.innerText = supervisors;
  dialog.close();
});

var supervisors = $$('li input[type="checkbox"]');
supervisors.forEach(e => {
  e.addEventListener('change', function (event) {
    $('.' + this.id).selected = this.checked;
  });
});

function update(query) {
  var supervisors = $$('li input[type="checkbox"]');
  var query = query.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
  supervisors.forEach(e => {
    var string = e.id.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
    if (string.toLowerCase().indexOf(query.toLowerCase()) === -1) {
      e.parentElement.hidden = true;
    } else {
      e.parentElement.hidden = false;
    }
  });
  supervisors.sort(function (a, b) {
    return b.checked - a.checked;
  });
  let ul = $('ul[class="dropdown"]');
  ul.innerHTML = null;
  supervisors.forEach(e => ul.append(e.parentNode));
}

button.addEventListener('click', function (event) {
  event.preventDefault();
  document.querySelector('body').classList.add('modal-open');
  dialog.show();
});

input.addEventListener('input', function(event) {
  update(event.target.value);
});

update("");

// Hide the other one if javascript loaded
$('#select-supervisors').hidden = true;

</script>
