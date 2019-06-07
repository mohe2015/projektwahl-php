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

  <select class="col" name="supervisors" multiple>
    <?php foreach ($users as $user): ?>
      <option><?php echo $user->name ?></option>
    <?php endforeach ?>
  </select>

  <button id="show-supervisors-dialog">Keine</button>
  <dialog id="dialog-supervisors">
    <h1>Betreuer</h1>
    <ul class="dropdown">
<?php foreach ($users as $user): ?>
      <li>
        <input type="checkbox" value="" id="<?php echo $user->name ?>">
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
var form = document.getElementById("form-supervisors");
var dialog = document.getElementById("dialog-supervisors");
var button = document.querySelector('#show-supervisors-dialog');

dialog.addEventListener('close', function onClose(e) {
  console.log(e);
  e.preventDefault();
  document.querySelector('body').classList.remove('modal-open');
});

document.querySelector('#save-supervisors').addEventListener('click', function(event) {
  event.preventDefault();
  var supervisors = [...dialog.querySelectorAll('input:checked')].map(x => x.id).join("; ") || "Keine";
  button.innerText = supervisors;
  dialog.close();
});

button.addEventListener('click', function (event) {
  event.preventDefault();
  document.querySelector('body').classList.add('modal-open');
  dialog.show();
});

</script>
