<?php
/*
Diese Software kann eine Projektwahl verwalten, wie sie beispielsweise für eine Projektwoche benötigt wird. 
Copyright (C) 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published
by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

require_once __DIR__ . '/../config.php';
try {
    $db = new PDO($database['url'], $database['username'], $database['password'], array(
  //    PDO::ATTR_PERSISTENT => true, // doesn't work with sqlite
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ));
} catch (PDOException $e) {
  die('<div class="alert alert-danger" role="alert">Fehler beim Verbinden zur Datenbank: ' . $e . '</div>');
}

try {
  $stmt = $db->query("CREATE TABLE IF NOT EXISTS projects (
  id INTEGER PRIMARY KEY,
  title VARCHAR(255) UNIQUE NOT NULL,
  info VARCHAR(4096) NOT NULL,
  place VARCHAR(256) NOT NULL,
  costs DECIMAL(4,2) NOT NULL,
  min_grade INTEGER NOT NULL,
  max_grade INTEGER NOT NULL,
  min_participants INTEGER NOT NULL,
  max_participants INTEGER NOT NULL,
  presentation_type VARCHAR(512) NOT NULL,
  requirements VARCHAR(1024) NOT NULL,
  random_assignments BOOLEAN NOT NULL
  );");
  $stmt->closeCursor();
  $stmt = null;

  $stmt = $db->query("CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY,
  name VARCHAR(64) UNIQUE NOT NULL,
  password_hash VARCHAR(255),
  type VARCHAR(8) NOT NULL,
  project_leader INTEGER,
  class VARCHAR(8),
  grade INTEGER,
  away BOOLEAN,
  password_changed BOOLEAN NOT NULL DEFAULT FALSE,
  in_project INTEGER,
  FOREIGN KEY (project_leader)
    REFERENCES projects(id)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT,
  FOREIGN KEY (in_project)
    REFERENCES projects(id)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT
  );");
  $stmt->closeCursor();
  $stmt = null;

  $stmt = $db->query("CREATE TABLE IF NOT EXISTS choices (
  rank INTEGER NOT NULL,
  project INTEGER NOT NULL,
  student INTEGER NOT NULL,
  PRIMARY KEY(project,student),
  FOREIGN KEY (project)
    REFERENCES projects(id)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT,
  FOREIGN KEY (student)
    REFERENCES users(id)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT
  );");
  $stmt->closeCursor();
  $stmt = null;

  $stmt = $db->query("CREATE TABLE IF NOT EXISTS settings (
  id INTEGER PRIMARY KEY,
  election_running BOOLEAN NOT NULL
  );");
  $stmt->closeCursor();
  $stmt = null;

  $db->exec("
  DROP TRIGGER IF EXISTS trigger_check_choices_grade;
  CREATE TRIGGER trigger_check_choices_grade
  BEFORE INSERT ON choices
  FOR EACH ROW
  BEGIN
    SELECT CASE WHEN     (SELECT min_grade FROM projects WHERE id = NEW.project) > (SELECT grade FROM users WHERE id = NEW.student)
            OR (SELECT max_grade FROM projects WHERE id = NEW.project) < (SELECT grade FROM users WHERE id = NEW.student) THEN
      RAISE(ABORT, 'Der Schüler passt nicht in die Altersbegrenzung des Projekts!')
    END;
  END;");

  $db->exec("
  DROP TRIGGER IF EXISTS trigger_update_project_check_choices_grade;
  CREATE TRIGGER trigger_update_project_check_choices_grade
  BEFORE UPDATE ON projects
  FOR EACH ROW
  BEGIN
    SELECT CASE WHEN (SELECT COUNT(*) FROM choices, users WHERE choices.project = NEW.id AND users.id = choices.student AND (users.grade < NEW.min_grade OR users.grade > NEW.max_grade)) > 0 THEN
      RAISE(ABORT, 'Geänderte Altersbegrenzung würde Wahlen ungültig machen!')
    END;
  END;");


  $db->exec("
  DROP TRIGGER IF EXISTS trigger_check_project_leader;
  CREATE TRIGGER trigger_check_project_leader BEFORE UPDATE ON users
  FOR EACH ROW
  BEGIN
    SELECT CASE WHEN (SELECT COUNT(*) FROM choices WHERE choices.project = NEW.project_leader AND choices.student = NEW.id) > 0 THEN
      RAISE(ABORT, 'Schüler kann Projekt nicht wählen, in dem er Projektleiter ist!')
    END;
  END;

  DROP TRIGGER IF EXISTS trigger_check_roject_leader_choices;
  CREATE TRIGGER trigger_check_roject_leader_choices BEFORE INSERT ON choices
  FOR EACH ROW
  BEGIN
    SELECT CASE WHEN (SELECT COUNT(*) FROM users WHERE users.project_leader = NEW.project AND users.id = NEW.student) > 0 THEN
      RAISE(ABORT, 'Schüler kann Projekt nicht wählen, in dem er Projektleiter ist!')
    END;
  END;");

  $stmt = $db->query("INSERT INTO settings (id, election_running) VALUES (1, false) ON CONFLICT DO NOTHING;");
  $stmt->closeCursor();
  $stmt = null;

  $stmt = $db->prepare("INSERT INTO users (name, password_hash, type) VALUES (:name, :password_hash, 'admin') ON CONFLICT DO NOTHING");
  $stmt->execute(array('name' => 'Admin', 'password_hash' => password_hash("password", PASSWORD_ARGON2ID, $options)));
  $stmt->closeCursor();
  $stmt = null;

  $db = null;

  echo '<div class="alert alert-success" role="alert">Installation erfolgreich! Der Standard-Account lautet:<br />Nutzername: Admin<br />Passwort: password';
  echo "<br /><a href=\"/\" class=\"alert-link\">Zur Startseite</a></div>";
} catch (PDOException $e) {
  print '<div class="alert alert-danger" role="alert">Fehler bei der Installation: ' . $e . '<br/>';
  print "Vielleicht hast du bereits alles installiert? Versuchs mal mit der <a href=\"/\" class=\"alert-link\">Startseite</a></div>";
}
?>