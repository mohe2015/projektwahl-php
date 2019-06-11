<div style="page-break-inside: avoid;">
  <h2><?php echo htmlspecialchars($student->name) ?> <a class="print-display-none" href="/students/edit.php?<?php echo $student->id ?>"><i class="fas fa-pen"></i></a> <a class="print-display-none" href="/students/delete.php?<?php echo $student->id ?>"><i class="fas fa-trash"></i></a></h2>
  <b>Klasse: </b><?php echo htmlspecialchars($student->class) ?><br>
  <b>Jahrgang: </b><?php echo htmlspecialchars($student->grade) ?><br>
  <b>Abwesend? </b><?php echo htmlspecialchars($student->away) ? "ja" : "nein" ?><br>
  <b>Projektleiter in: </b><?php echo htmlspecialchars($student->project_leader) ?><br>
  <b>in Projekt: </b><?php echo htmlspecialchars($student->in_project) ?><br>
</div>
