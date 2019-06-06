<div style="page-break-inside: avoid;">
  <h2><?php echo htmlspecialchars($project->title) ?></h2>
  <b>Info: </b><?php echo htmlspecialchars($project->info) ?><br>
  <b>Ort/Raum: </b><?php echo htmlspecialchars($project->place) ?><br>
  <b>Ich benötige: </b><?php echo htmlspecialchars($project->requirements) ?><br>
  <b>Präsentationsart: </b><?php echo htmlspecialchars($project->presentation_type) ?><br>
  <b>Kosten: </b><?php echo htmlspecialchars($project->costs) ?><br>
  <b>Jahrgangsstufe: </b><?php echo htmlspecialchars($project->min_grade) ?> - <?php echo htmlspecialchars($project->max_grade) ?><br>
  <b>Teilnehmeranzahl: </b><?php echo htmlspecialchars($project->min_participants) ?> - <?php echo htmlspecialchars($project->max_participants) ?><br>
  <b>Projektleiter: </b>TODO<br>
  <b>Zufällige Projektzuweisungen erlaubt: </b><?php echo htmlspecialchars($project->random_assignments) ? "ja" : "nein" ?><br>
</div>
