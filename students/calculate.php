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
  return "S$choice->student" . "_P$choice->project";
}

// TODO put in Students::
$stmt = $db->prepare("SELECT * FROM users WHERE type = 'student' AND away = FALSE;");
$stmt->execute();
$assoc_students = $stmt->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_CLASS, 'Student');

// TODO put in Projects::
$stmt = $db->prepare('SELECT * FROM projects;');
$stmt->execute();
$assoc_projects = $stmt->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_CLASS, 'Project');

// http://www.gnu.org/software/glpk/
// http://lpsolve.sourceforge.net/
// https://github.com/coin-or/Cbc

// glpsol --lp calculate.lp
// cbc calculate.lp


// TODO FIXME away students
global $db;
$stmt = $db->prepare("SELECT users.*, choices.* FROM users LEFT JOIN choices ON id = choices.student AND choices.rank != 0 WHERE type = 'student' AND away = FALSE ORDER BY id;"); // TODO FIXME rank!=0
$stmt->execute();
$choices = $stmt->fetchAll(PDO::FETCH_CLASS, 'Choice');

// maximize rating points
$out = fopen('/tmp/problem.lp', 'w'); // TODO temp file
//$out = fopen('php://output', 'w');
fwrite($out, "Maximize\n");
fwrite($out, " obj:");
foreach ($choices as $choice) {
  if ($choice->rank === NULL) {
    continue;
  }
  fwrite($out, " + " . rank2points($choice->rank) . " " . choice2string($choice));
}
fwrite($out, "\nSubject To");

$grouped_choices = array();
foreach ($choices as $choice) {
  if ($choice->rank === NULL) {
    $grouped_choices[$choice->id] = array();
  } else {
    $grouped_choices[$choice->id][] = $choice;
  }
}

foreach ($grouped_choices as $student_id => &$choices) {
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
  // student in exactly one project
  if ($rank_count[1] == 1 && $rank_count[2] == 1 && $rank_count[3] == 1 && $rank_count[4] == 1 && $rank_count[5] == 1) {
    fwrite($out, "\n S$student_id" . "_P: ");
    // valid vote
    foreach ($choices as $choice) {
      fwrite($out, " + " . choice2string($choice));
    }
  } else {
    continue;
    fwrite($out, "\n S$student_id" . "_P: ");
    // invalid vote
    $choices = array();
    foreach ($assoc_projects as $project_id => $project) {
      if (!$project->random_assignments) {
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
    fwrite($out, " + P$project_leader" . "_e");
  }
  fwrite($out, " = 1");

  // student only in project if it exists
  foreach ($choices as $choice) {
    # 0 or 1
    # 0
    #   not in project (0) and project exists (0)
    # 1
    #   not in project (0) and project doesn't exist (1)
    #   in project (1)     and project exists (0)
    # 2
    #   in project (1)     and project doesn't exist (1)
    fwrite($out, "\n S$choice->student" . "_P$choice->project" . "_e1: " . choice2string($choice) . " + P$choice->project" . "_ne <= 1");
    fwrite($out, "\n S$choice->student" . "_P$choice->project" . "_e2: " . choice2string($choice) . " + P$choice->project" . "_ne >= 0");
  }
}
unset($choices); // break the reference with the last element

$project_grouped_choices = array();
foreach ($grouped_choices as $student_id => $choices) {
  foreach ($choices as $choice) {
    $project_grouped_choices[$choice->project][] = $choice;
  }
}

// project not overfilled / underfilled
foreach ($assoc_projects as $project_id => $project) {
  $choices = $project_grouped_choices[$project_id];
  fwrite($out, "\n P$project_id" . "_u: ");
  foreach ($choices as $choice) {
    fwrite($out, " + " . choice2string($choice));
  }
  fwrite($out, " + $project->min_participants P$choice->project" . "_ne >= $project->min_participants");

  fwrite($out, "\n P$project_id" . "_o: ");
  foreach ($choices as $choice) {
    fwrite($out, " + " . choice2string($choice));
  }
  fwrite($out, " + $project->max_participants P$choice->project" . "_ne <= $project->max_participants");

  fwrite($out, "\n P$project_id" . "_e_o_ne: P$project_id" . "_e + P$project_id" . "_ne = 1");
}

fwrite($out, "\nBinary\n");

foreach ($assoc_projects as $project_id => $project) {
  $choices = $project_grouped_choices[$project_id];
  // TODO verify that the above loop really loops over all projects that exist (I think it doesn't if nobody is in the age range or nobody didnt vote and nobody voted
  fwrite($out, " P$project_id" . "_e P$project_id" . "_ne");
  foreach ($choices as $choice) {
    fwrite($out, " " . choice2string($choice));
  }
}

fwrite($out, "\nEnd\n");
fclose($out);

passthru("glpsol --lp /tmp/problem.lp -w /tmp/solution.txt");
?>
