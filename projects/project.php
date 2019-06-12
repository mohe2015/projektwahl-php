<div style="page-break-inside: avoid;">
  <h2><?php echo htmlspecialchars($project->title) ?> <a class="print-display-none" href="/projects/edit.php?<?php echo $project->id ?>"><i class="fas fa-pen"></i></a> <a class="print-display-none" href="/projects/delete.php?<?php echo $project->id ?>"><i class="fas fa-trash"></i></a></h2>
  <b>Info: </b><?php echo htmlspecialchars($project->info) ?><br>
  <b>Ort/Raum: </b><?php echo htmlspecialchars($project->place) ?><br>
  <b>Ich benötige: </b><?php echo htmlspecialchars($project->requirements) ?><br>
  <b>Präsentationsart: </b><?php echo htmlspecialchars($project->presentation_type) ?><br>
  <b>Kosten: </b><?php echo htmlspecialchars($project->costs) ?><br>
  <b>Jahrgangsstufe: </b><?php echo htmlspecialchars($project->min_grade) ?> - <?php echo htmlspecialchars($project->max_grade) ?><br>
  <b>Teilnehmeranzahl: </b><?php echo htmlspecialchars($project->min_participants) ?> - <?php echo htmlspecialchars($project->max_participants) ?><br>
  <b>Projektleiter: </b>
<?php
echo join(', ', array_map(function($project_leader) {
    return $project_leader->name;
}, $project_with_project_leaders));
?>
  <br>
  <b>Zufällige Projektzuweisungen erlaubt: </b><?php echo htmlspecialchars($project->random_assignments) ? "ja" : "nein" ?><br>
</div>
