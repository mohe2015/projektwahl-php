<?php
header("Content-Type: text/plain");
$allowed_users = array("admin");
require_once __DIR__ . '/../header.php';

function rank2points($rank) {
  switch ($rank) {
    case 1:
      return 11;
    case 2:
      return 7;
    case 3:
      return 4;
    case 4:
      return 2;
    case 5:
      return 1;
    default:
      throw new Error("unknown rank: $rank");
  }
}

function choice2string($choice) {
  return "Student_$choice->student" . "_Project_$choice->project". "_Rank_$choice->rank";
}

// TODO put in Students::
// TODO fixme away students
$stmt = $db->prepare('SELECT * FROM users WHERE type = "student";');
$stmt->execute();
$assoc_students = $stmt->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_CLASS, 'Student');

// TODO put in Projects::
$stmt = $db->prepare('SELECT * FROM projects;');
$stmt->execute();
$assoc_projects = $stmt->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_CLASS, 'Project');


// http://www.gnu.org/software/glpk/
// http://lpsolve.sourceforge.net/
// https://github.com/coin-or/Cbc
// https://scip.zib.de/

// maximize rating points

//$out = fopen('problem.lp', 'w'); // TODO temp file
$out = fopen('php://output', 'w');
fwrite($out, "Maximize\n");
fwrite($out, " obj:");

// TODO FIXME away students
$choices = Choices::all();
foreach ($choices as $choice) {
  fwrite($out, " + " . rank2points($choice->rank) . " " . choice2string($choice));
}
fwrite($out, "\nSubject To:\n");

$grouped_choices = array();
foreach ($choices as $choice) {
    $grouped_choices[$choice->student][] = $choice;
}

// TODO fixme no votes -> no student in that array
foreach ($grouped_choices as $student_id => $choices) {
  $student = $assoc_students[$student_id];
  $rank_count = array(
    1 => 0,
    2 => 0,
    3 => 0,
    4 => 0,
    5 => 0
  );
  foreach ($choices as $choice) {
    $rank_count[$choice->rank]++;
  }
  if ($rank_count[1] == 1 && $rank_count[2] == 1 && $rank_count[3] == 1 && $rank_count[4] == 1 && $rank_count[5] == 1) {
    // valid vote
    fwrite($out, " Student_$choice->student" . "_in_one_Project: 1 =");
    foreach ($choices as $choice) {
      fwrite($out, " + " . choice2string($choice));
    }
  } else {
    // invalid vote
    $choices = array();
    foreach ($assoc_projects as $project_id => $project) {
      if (!$project->random_assignments) { // TODO testing
        continue;
      }
      if ($student->grade < $project->min_grade) {
        continue;
      }
      if ($student->grade > $project->max_grade) {
        continue;
      }
      $choice = new Choice(array(
        'project' => $project_id,
        'student' => $student_id,
        'rank' => -1,
      ));
      $choices[] = $choice;
      fwrite($out, " + " . choice2string($choice));
    }
  }
  $project_leader = $student->project_leader;
  if ($project_leader) {
    fwrite($out, " + Project_$project_leader" . "_exists"); // TODO check if it works
  }


}

fclose($out);
?>
