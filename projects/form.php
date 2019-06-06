
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
<!--
  <select class="col" name="supervisors" multiple>
    <option>Volvo</option>
    <option>Saab</option>
    <option>Opel</option>
    <option>Audi</option>
  </select>
-->
  <button>Keine</button>
  <dialog id="dialog-supervisors" open>
    <ul class="dropdown">
      <li>
        <input type="checkbox" value="" id="Peter">
        <label for="Peter">
          Peter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Bob">
        <label for="Bob">
          Bob
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Klaus">
        <label for="Klaus">
          Klaus
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
      <li>
        <input type="checkbox" value="" id="Dieter">
        <label for="Dieter">
          Dieter
        </label>
      </li>
    </ul>
    <menu>
      <button id="cancel-supervisors">Abbbrechen</button>
      <button id="save-supervisors">Okay</button>
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

document.querySelector('body').classList.add('modal-open');

dialog.addEventListener('close', function onClose(e) {
  console.log(e);
  e.preventDefault();
  document.querySelector('body').classList.remove('modal-open');
});

document.querySelector('#cancel-supervisors').addEventListener('click', function(event) {
  event.preventDefault();
  dialog.close();
});

document.querySelector('#save-supervisors').addEventListener('click', function(event) {
  event.preventDefault();
  dialog.close();
});

</script>
