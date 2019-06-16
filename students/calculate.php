<?php
function disable_ob() {
    // Turn off output buffering
    ini_set('output_buffering', 'off');
    // Turn off PHP output compression
    ini_set('zlib.output_compression', false);
    // Implicitly flush the buffer(s)
    ini_set('implicit_flush', true);
    ob_implicit_flush(true);
    // Clear, and turn off output buffering
    while (ob_get_level() > 0) {
        // Get the curent level
        $level = ob_get_level();
        // End the buffering
        ob_end_clean();
        // If the current level has not changed, abort
        if (ob_get_level() == $level) break;
    }
    // Disable apache output buffering/compression
    if (function_exists('apache_setenv')) {
        apache_setenv('no-gzip', '1');
        apache_setenv('dont-vary', '1');
    }
}
disable_ob();

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

$stmt = $db->prepare("SELECT * FROM users WHERE type = 'student' AND away = FALSE ORDER BY class,name;");
$stmt->execute();
$assoc_students = $stmt->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_CLASS, 'Student');

$stmt = $db->prepare('SELECT * FROM projects ORDER BY title;');
$stmt->execute();
$assoc_projects = $stmt->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_CLASS, 'Project');

// http://www.gnu.org/software/glpk/
// http://lpsolve.sourceforge.net/
// https://github.com/coin-or/Cbc

// glpsol --lp calculate.lp
// cbc /tmp/problem.lp

// maximize rating points
$problem_filename = tempnam("/tmp", "problem");
$out = fopen($problem_filename, 'w');
$stdout = fopen('php://output', 'w');

$choices = Choices::allWithUsers();

$grouped_choices = Choices::groupChoices($choices);

$assoc_students = Choices::validateChoices($grouped_choices, $assoc_students);

fwrite($out, "Maximize\n");
fwrite($out, " obj:");
foreach ($choices as $choice) {
  $student = $assoc_students[$choice->id];
  if ($student->valid) {
    fwrite($out, " + " . rank2points($choice->rank) . " " . choice2string($choice));
  }
}
foreach ($assoc_projects as $project_id => $project) {
  fwrite($out, " - 11000 P$project_id" . "_o"); // TODO FIXME
  //fwrite($out, " - 11000 P$project_id" . "_u");
}
foreach ($assoc_students as $student_id => $student) {
  //fwrite($out, " - 11000 S$student_id" . "_f1");
  //fwrite($out, " - 11000 S$student_id" . "_f2");
  //fwrite($out, " - 11000 S$student_id" . "_f3");
}
fwrite($out, "\nSubject To");


foreach ($grouped_choices as $student_id => $student_choices) {
  $student = $assoc_students[$student_id];
  if ($student->valid) {
    fwrite($out, "\n S$student_id" . "_P: ");
    // valid vote
    foreach ($student_choices as $choice) {
      fwrite($out, " + " . choice2string($choice));
    }
  } else {
    fwrite($out, "\n S$student_id" . "_P: ");
    // invalid vote
    $grouped_choices[$student_id] = array();
    foreach ($assoc_projects as $project_id => $project) {
      if ($student->grade < $project->min_grade) {
        continue;
      }
      if ($student->grade > $project->max_grade) {
        continue;
      }
      if (!$project->random_assignments) {
        continue;
      }
      $choice = new Choice(array(
        'project' => $project_id,
        'student' => $student_id,
        'rank' => -1,
      ));
      $grouped_choices[$student_id][] = $choice;
      fwrite($out, " + " . choice2string($choice));
    }
  }
  $project_leader = $student->project_leader;
  if ($project_leader) {
    fwrite($out, " + P$project_leader" . "_e");
  }
  //fwrite($out, " + S$student_id" . "_f2");
  //fwrite($out, " - S$student_id" . "_f3");
  fwrite($out, " = 1");
}

// student only in project if it exists
foreach ($grouped_choices as $student_id => $student_choices) {
  foreach ($student_choices as $choice) {
    # 0 or 1
    # 0
    #   not in project (0) and project exists (0)
    # 1
    #   not in project (0) and project doesn't exist (1)
    #   in project (1)     and project exists (0)
    # 2
    #   in project (1)     and project doesn't exist (1)
    fwrite($out, "\n S$choice->student" . "_P$choice->project" . "_e1: " . choice2string($choice) . " + P$choice->project" . "_ne");
    //fwrite($out, " - S$student_id" . "_f1");
    fwrite($out, " <= 1");
    fwrite($out, "\n S$choice->student" . "_P$choice->project" . "_e2: " . choice2string($choice) . " + P$choice->project" . "_ne");
    //fwrite($out, " - S$student_id" . "_f1");
    fwrite($out, " >= 0");
  }
}

$project_grouped_choices = array();
foreach ($grouped_choices as $student_id => $student_choices) {
  foreach ($student_choices as $choice) {
    $project_grouped_choices[$choice->project][] = $choice;
  }
}

// project not overfilled / underfilled
foreach ($assoc_projects as $project_id => $project) {
  $choices = $project_grouped_choices[$project_id];
  fwrite($out, "\n P$project_id" . "_check_u: ");
  foreach ($choices as $choice) {
    fwrite($out, " + " . choice2string($choice));
  }
  fwrite($out, " + P$project_id" . "_u");
  fwrite($out, " + $project->min_participants P$choice->project" . "_ne >= $project->min_participants");

  fwrite($out, "\n P$project_id" . "_check_o: ");
  foreach ($choices as $choice) {
    fwrite($out, " + " . choice2string($choice));
  }
  fwrite($out, " - P$project_id" . "_o");
  fwrite($out, " + $project->max_participants P$choice->project" . "_ne <= $project->max_participants");

  fwrite($out, "\n P$project_id" . "_e_o_ne: P$project_id" . "_e + P$project_id" . "_ne = 1");
}

fwrite($out, "\nBounds");

foreach ($assoc_projects as $project_id => $project) {
  fwrite($out, "\n 0 <= P$project_id" . "_o");
  //fwrite($out, "\n 0 <= P$project_id" . "_u");
}

foreach ($assoc_students as $student_id => $student) {
  //fwrite($out, "\n 0 <= S$student_id" . "_f2");
  //fwrite($out, "\n 0 <= S$student_id" . "_f3");
}

fwrite($out, "\nGeneral\n");

foreach ($assoc_projects as $project_id => $project) {
  fwrite($out, " P$project_id" . "_o");
  //fwrite($out, " P$project_id" . "_u");
}

foreach ($assoc_students as $student_id => $student) {
  //fwrite($out, " S$student_id" . "_f2");
  //fwrite($out, " S$student_id" . "_f3");
}

fwrite($out, "\nBinary\n");

foreach ($assoc_students as $student_id => $student) {
  //fwrite($out, " S$student_id" . "_f1");
}

foreach ($assoc_projects as $project_id => $project) {
  $choices = $project_grouped_choices[$project_id];
  fwrite($out, " P$project_id" . "_e P$project_id" . "_ne");
  foreach ($choices as $choice) {
    fwrite($out, " " . choice2string($choice));
  }
}

fwrite($out, "\nEnd\n");
fclose($out);

// glpsol --check --lp /tmp/problem.lp --wmps /tmp/problem.mips
// now lp_solve would also work

$solution_filename = tempnam("/tmp", "solution");
passthru("glpsol --lp $problem_filename -o $solution_filename --dual");

$solution_file = fopen("$solution_filename", "r");

$solution = array();
while (!feof($solution_file))  {
  $result = fgets($solution_file);
  $parts = preg_split('/\s+/', $result, -1, PREG_SPLIT_NO_EMPTY);
  if (count($parts) >= 4) {
    $name = $parts[1];
    $value = (int)$parts[3];
    $solution[$name] = $value;
    //print($name . ":" . $value . "\n");
  }
}

fclose($solution_file);

$rank_count = array(
  -1 => 0,
  1 => 0,
  2 => 0,
  3 => 0,
  4 => 0,
  5 => 0
);

foreach ($assoc_projects as $project_id => $project) {
  $choices = $project_grouped_choices[$project_id];
  $sum = 0;
  foreach ($choices as $choice) {
    if ($solution[choice2string($choice)] === 1) {
      $sum++;
      $rank_count[$choice->rank]++;
      print("[$choice->rank] " . $assoc_students[$choice->student]->name . " in " . $project->title . "\n");
    }
  }
  if ($solution["P$project_id" . "_e"] === 1) {
    print($project->title . " findet statt. ($sum / $project->max_participants)\n");
  }
  if ($solution["P$project_id" . "_ne"] === 1) {
    print($project->title . " findet NICHT statt.\n");
  }
}

var_dump($rank_count);

foreach ($assoc_projects as $project_id => $project) {
  if ($solution["P$project_id" . "_o"] !== 0) {
    print($project->title . " overflow: " . $solution["P$project_id" . "_o"] . "\n");
  }
}

// TODO print project leaders

// SELECT SUM(max_participants) FROM projects;

// SELECT COUNT(*) FROM users WHERE type = 'student' AND NOT away AND project_leader IS NULL;

?>
