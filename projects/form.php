
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
    <option>Volvo</option>
    <option>Saab</option>
    <option>Opel</option>
    <option>Audi</option>
  </select>
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
