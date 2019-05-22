<?php
require_once '../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
?>

<h1>Projekt erstellen</h1>

<form>

<div class="form-group">
  <label class="col">Titel*:</label>
  <input class="col" type="text" />
</div>

<div class="form-group">
  <label class="col">Info*:</label>
  <textarea class="col"></textarea>
</div>

<div class="form-group">
  <label class="col">Ich benötige:</label>
  <textarea class="col"></textarea>
</div>

<div class="form-group">
  <label class="col">Präsentationsart:</label>
  <input class="col" type="text" />
</div>

<div class="form-group">
  <label class="col">Ort/Raum*:</label>
  <input class="col" type="text" />
</div>

<div class="form-group">
  <label class="col">Kosten:</label>
  <input class="col" type="number" />
</div>

<div class="form-group">
  <label class="col">Jahrgangsstufe*:</label>
  <div class="col">
    <input type="number" />
    <span>bis</span>
    <input type="number" />
  </div>
</div>

<div class="form-group">
  <label class="col">Teilnehmeranzahl*:</label>
  <div class="col">
    <input type="number" />
    <span>bis</span>
    <input type="number" />
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

<div class="form-group">
  <button type="submit" class="w-100">Projekt erstellen</button>
</div>

</form>
