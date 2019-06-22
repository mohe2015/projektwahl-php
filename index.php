<?php
$allowed_users = array("student", "teacher", "admin");
require_once __DIR__ . '/head.php';

$user = end($_SESSION['users']); // TODO this needs to be updated from database

if (!$settings->election_running && $user->type !== 'admin') {
  require_once __DIR__ . '/head.php';
  if ($user->in_project !== NULL) {
    // TODO highlight
    echo("<p>Die Wahl ist beendet! Du bist" . ($user->in_project == $user->project_leader ? " Projektleiter" : "") . " im Projekt " . htmlspecialchars(Projects::find($user->in_project)->title) . ".</p>");
  } else {
    echo("<p>Die Wahl ist beendet!</p>");
  }
}
?>
<h1>Willkommen</h1>

<h2>Credits</h2>

<p>Diese Software wurde von Moritz Hedtke entwickelt.</p>

<p>Der Quellcode ist aus Transparenzgründen unter <a target="_blank" rel="noopener noreferrer" href="https://github.com/mohe2015/projektwahl-php">https://github.com/mohe2015/projektwahl-php</a> einzusehen.</p>
