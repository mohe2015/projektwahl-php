<?php
header("Content-Type: text/plain");
$allowed_users = array("admin");
require_once __DIR__ . '/../header.php';

$students = Students::all();
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
fwrite($out, " obj: ");
?>

<?php foreach ($students as $student) :?>
  <?php fwrite($out, $student->name) ?>
<?php endforeach;
fclose($out);
?>
