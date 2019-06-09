<?php
$allowed_users = array("student", "teacher", "admin");
require_once __DIR__ . '/head.php';
?>
<h1>Willkommen</h1>

<h2>Credits</h2>

<p>Diese Software wurde von Moritz Hedtke entwickelt.</p>

<p>Der Quellcode ist aus Transparenzgründen unter <a target="_blank" rel="noopener noreferrer" href="https://github.com/mohe2015/projektwahl-php">https://github.com/mohe2015/projektwahl-php</a> einzusehen.</p>

CREATE EXTENSION dblink;

INSERT INTO projects (title, info, place, costs, min_grade, max_grade, min_participants, max_participants, presentation_type, requirements, random_assignments)
SELECT titel, info, place, costs, min_grade, max_grade, min_participants, max_participants, presentation_type, helper_count, random_assignment_allowed FROM dblink('dbname=projektwahl_production', 'SELECT titel, info, place, costs, min_grade, max_grade, min_participants, max_participants, presentation_type, helper_count, random_assignment_allowed FROM projects') AS t1(titel, info, place, costs, min_grade, max_grade, min_participants, max_participants, presentation_type, helper_count, random_assignment_allowed);
