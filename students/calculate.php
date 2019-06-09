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

// http://www.gnu.org/software/glpk/
// http://lpsolve.sourceforge.net/
// https://github.com/coin-or/Cbc
// https://scip.zib.de/

// maximize rating points

// TODO assumed that student leaders always are student leaders
// TODO assumed that all projects exist (TODO remove the ones that cannot possibly exist)
//$out = fopen('problem.lp', 'w'); // TODO temp file
$out = fopen('php://output', 'w');
fwrite($out, "Maximize\n");
fwrite($out, " obj:");

$choices = Choices::all();
foreach ($choices as $choice) {
  fwrite($out, " + " . rank2points($choice->rank) . " Student_$choice->student" . "_Project_$choice->project". "_Rank_$choice->rank");
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
    fwrite($out, "valid");
  }
}

fclose($out);
?>
