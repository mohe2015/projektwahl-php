<?php
require_once '../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
if (!empty($_POST)) {
  $project = new Project($_POST);
  try {
    Projects::save($project);
  } catch (Exception $e) {
    print $e->getMessage();
  }
}
?>

<h1>Projekt erstellen</h1>

<form method="post">

<div class="form-group">
  <label class="col">Titel*:</label>
  <input class="col" type="text" name="title" />
</div>

<div class="form-group">
  <label class="col">Info*:</label>
  <textarea class="col" name="info"></textarea>
</div>

<div class="form-group">
  <label class="col">Ich benötige:</label>
  <textarea class="col" name="requirements"></textarea>
</div>

<div class="form-group">
  <label class="col">Präsentationsart:</label>
  <input class="col" type="text" name="presentation_type" />
</div>

<div class="form-group">
  <label class="col">Ort/Raum*:</label>
  <input class="col" type="text" name="room" />
</div>

<div class="form-group">
  <label class="col">Kosten:</label>
  <input class="col" type="number" name="costs" />
</div>

<div class="form-group">
  <label class="col">Jahrgangsstufe*:</label>
  <div class="col">
    <input type="number" name="min_grade" />
    <span>bis</span>
    <input type="number" name="min_grade" />
  </div>
</div>

<div class="form-group">
  <label class="col">Teilnehmeranzahl*:</label>
  <div class="col">
    <input type="number" name="min_participants" />
    <span>bis</span>
    <input type="number" name="max_participants" />
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
    <input type="checkbox" name="random_assignments">
    Zufällige Projektzuweisungen erlaubt
  </label>
</div>

<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />

<div class="form-group">
  <button type="submit" class="w-100">Projekt erstellen</button>
</div>

</form>
