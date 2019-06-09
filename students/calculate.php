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
  return "Student_$choice->student" . "_in_Project_$choice->project";
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
global $db;
$stmt = $db->prepare('SELECT users.*, choices.* FROM users LEFT JOIN choices ON id = choices.student AND choices.rank != 0 WHERE type = "student" ORDER BY id;'); // TODO FIXME rank!=0
$stmt->execute();
$choices = $stmt->fetchAll(PDO::FETCH_CLASS, 'Choice');

foreach ($choices as $choice) {
  if ($choice->rank === NULL) {
    continue;
  }
  fwrite($out, " + " . rank2points($choice->rank) . " " . choice2string($choice));
}
fwrite($out, "\nSubject To:");

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
  fwrite($out, "\n Student_$student_id" . "_in_one_Project: 1 =");
  if ($rank_count[1] == 1 && $rank_count[2] == 1 && $rank_count[3] == 1 && $rank_count[4] == 1 && $rank_count[5] == 1) {
    // valid vote
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
    fwrite($out, "\n Student_$choice->student" . "_only_in_Project_$choice->project" . "_if_exists: 0 <= " . choice2string($choice) . " + Project_$choice->project" . "_not_exists <= 1");
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
foreach ($project_grouped_choices as $project_id => $choices) {
  // TODO verify that the above loop really loops over all projects that exist (I think it doesn't if nobody is in the age range or nobody didnt vote and nobody voted it
  $project = $assoc_projects[$project_id];
  fwrite($out, "\n Project_$project_id" . "_not_underfilled: ");
  foreach ($choices as $choice) {
    fwrite($out, " + " . choice2string($choice));
  }
  fwrite($out, " + $project->min_participants Project_$choice->project" . "_not_exists >= $project->min_participants");

  fwrite($out, "\n Project_$project_id" . "_not_overfilled: ");
  foreach ($choices as $choice) {
    fwrite($out, " + " . choice2string($choice));
  }
  fwrite($out, " + $project->max_participants Project_$choice->project" . "_not_exists <= $project->max_participants");

  fwrite($out, "\n Project_$project_id" . "_exists_or_not_exists: Project_$project_id" . "_exists + Project_$project_id" . "_not_exists = 1");
}

fwrite($out, "\nBinary\n");

foreach ($project_grouped_choices as $project_id => $choices) {
  // TODO verify that the above loop really loops over all projects that exist (I think it doesn't if nobody is in the age range or nobody didnt vote and nobody voted
  fwrite($out, " Project_$project_id" . "_exists Project_$project_id" . "_not_exists");
  foreach ($choices as $choice) {
    fwrite($out, " " . choice2string($choice));
  }
}
// TODO FIXME

fwrite($out, "\nEnd");

fclose($out);
?>
