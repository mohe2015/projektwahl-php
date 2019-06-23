<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../head.php';

$stmt = $db->prepare("SELECT id, * FROM users WHERE type = 'student' ORDER BY class,name;");
$stmt->execute();
$assoc_students = $stmt->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_CLASS, 'Student');

$choices = Choices::allWithUsersWithAway();

$grouped_choices = Choices::groupChoices($choices);

$assoc_students = Choices::validateChoices($grouped_choices, $assoc_students);
?>

<h1>Schüler</h1>

<a href="/students/new.php" class="button">Neuer Schüler</a>
<a href="/students/import.php" class="button">Schüler importieren</a>
<form class="inline-block" method="POST" action="generate_passwords.php">
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
  <button type="submit" href="/teachers/generate_passwords.php" class="button">Passwortliste generieren</button>
</form>
<a href="/students/not_voted.php" class="button">Schüler ohne gewählte Projekte</a>
<a href="/students/calculate.php" class="button">Projektzuordnungen berechnen</a>
<br>

<span style="background-color: green;">Gültig gewählt</span>
<span style="background-color: orange;">Ungültig gewählt</span>
<span style="background-color: red;">Nicht gewählt</span>
<span style="background-color: LightSeaGreen;">vorraussichtlich Projektleiter</span>
<span style="background-color: grey;">Abwesend</span>

<input class="w-100" type="search" id="search" placeholder="Suche nach Name oder Klasse">

<script>
var input = $('#search');

function update(query) {
  var students = $$('tr');
  var query = query.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
  students.forEach(e => {
    var string = e.id.replace("-", " ").normalize('NFD').replace(/[\u0300-\u036f]/g, "");
    if (string.toLowerCase().indexOf(query.toLowerCase()) === -1) {
      e.hidden = true;
    } else {
      e.hidden = false;
    }
  });
}

input.addEventListener('input', function(event) {
  update(event.target.value);
});

</script>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Klasse</th>
          <th scope="col">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($grouped_choices as $student_id => $student_choices):
          $student = $assoc_students[$student_id];
           ?>
          <tr id="<?php echo str_replace(" ", "-", $student->name . " " . $student->class) ?>" style="background-color: <?php echo $student->away ? 'grey' : ($student->project_leader ? 'LightSeaGreen' : ($student->valid ? 'green' : (count($student_choices) > 0 ? 'orange' : 'red'))) ?>;">
            <td><a href="/students/view.php?<?php echo $student->id ?>"><?php echo htmlspecialchars($student->name) ?></a></td>
            <td><?php echo htmlspecialchars($student->class) ?></td>
            <td>
              <a href="/students/edit.php?<?php echo $student->id ?>"><i class="fas fa-pen"></i></a>
              <form class="inline-block" method="post" action="edit.php?<?php echo $student->id ?>">
                <input type="hidden" name="away" value="<?php echo $student->away ? "" : "checked" ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                <button class="a" type="submit"><i class="fas <?php echo $student->away ? "fa-user-slash" : "fa-user" ?>"></i></button>
              </form>
              <form class="inline-block" method="post" action="edit.php?<?php echo $student->id ?>">
                <input type="hidden" name="password" value="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                <button class="a" type="submit"><i class="fas fa-key"></i></button>
              </form>
              <form class="inline-block" method="post" action="sudo.php?<?php echo $student->id ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                <button class="a" type="submit"><i class="fas fa-sign-in-alt"></i></button>
              </form>
              <a href="/students/delete.php?<?php echo $student->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
  </table>
</div>
