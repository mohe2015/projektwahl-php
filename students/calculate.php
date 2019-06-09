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

$choices = Choices::all();
foreach ($choices as $choice) {
  fwrite($out, " + " . rank2points($choice->rank) . " " . choice2string($choice));
}
fwrite($out, "\nSubject To:\n");

$grouped_choices = array();
foreach ($choices as $choice) {
    $grouped_choices[$choice->student][] = $choice;
}

foreach ($grouped_choices as $choices) {
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
    // TODO FIXME
  }
  var_dump($grouped_choices);
}

fclose($out);
?>
