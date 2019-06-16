<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../head.php';

$stmt = $db->prepare("SELECT * FROM users WHERE type = 'student' AND away = FALSE ORDER BY class,name;");
$stmt->execute();
$assoc_students = $stmt->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_CLASS, 'Student');

$choices = Choices::allWithUsers();

$grouped_choices = Choices::groupChoices($choices);

$assoc_students = Choices::validateChoices($grouped_choices, $assoc_students);
?>

<h1>Schüler, die noch nicht gewählt haben</h1>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Klasse</th>
        </tr>
      </thead>
      <tbody>
      <?php
      foreach ($grouped_choices as $student_id => $student_choices) {
        $student = $assoc_students[$student_id];
        if (!$student->valid): ?>
          <tr>
            <td><?php echo htmlspecialchars($student->name) . ($student->project_leader ? " (vmtl. Projektleiter)" : "") ?></td>
            <td><?php echo htmlspecialchars($student->class) ?></td>
          </tr>
        <?php endif;
      } ?>
      </tbody>
  </table>
</div>
