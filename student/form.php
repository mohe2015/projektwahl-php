<form method="post">

<div class="form-group">
  <label class="col">Name*:</label>
  <input class="col" type="text" name="name" value="<?php echo htmlspecialchars($student->name) ?>" />
</div>

<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />

<div class="form-group">
  <button type="submit" class="w-100">Schüler speichern</button>
</div>

</form>
