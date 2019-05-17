<form method="post">

<div class="form-group">
  <label class="col">Name*:</label>
  <input class="col" type="text" name="name" value="<?php echo htmlspecialchars($student->name) ?>" />
</div>

<div class="form-group">
  <label class="col">Klasse*:</label>
  <input class="col" type="text" name="class" value="<?php echo htmlspecialchars($student->class) ?>" />
</div>

<div class="form-group">
  <label class="col">Jahrgang*:</label>
  <input class="col" type="number" name="grade" value="<?php echo htmlspecialchars($student->grade) ?>" />
</div>

<div class="form-group">
  <label>
    <input type="checkbox" name="away" <?php echo (!empty($student->away)) ? "checked" : "" ?>>
    Abwesend
  </label>
</div>

<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />

<div class="form-group">
  <button type="submit" class="w-100">Schüler speichern</button>
</div>

</form>
