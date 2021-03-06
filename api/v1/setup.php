<?php
/*
SPDX-FileCopyrightText: 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>
SPDX-License-Identifier: AGPL-3.0-or-later

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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  die();
}

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
  // (strftime('%s','now'))
  $stmt = $db->query("CREATE TABLE IF NOT EXISTS sessions (
  session_id BLOB PRIMARY KEY,
  created_at INTEGER NOT NULL,
  updated_at INTEGER NOT NULL
  );");
  $stmt->closeCursor();
  $stmt = null;

  // this second table is needed to allow sudoing into accounts as admin
  // maybe combine the two tables but idk

  // id is autoincrement, needed for the order
  // session_id cryptographically random string
  // user_id the user as which you are logged in
  $stmt = $db->query("CREATE TABLE IF NOT EXISTS session_users (
  id INTEGER PRIMARY KEY NOT NULL,
  session_id BLOB NOT NULL,
  user_id INTEGER NOT NULL,
  FOREIGN KEY (session_id)
    REFERENCES sessions(session_id)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT,
  FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT
  );");
  $stmt->closeCursor();
  $stmt = null;

  $stmt = $db->query("CREATE TABLE IF NOT EXISTS projects (
  id INTEGER PRIMARY KEY,
  title VARCHAR(255) UNIQUE NOT NULL,
  info VARCHAR(4096) NOT NULL,
  place VARCHAR(256) NOT NULL,
  costs DECIMAL(4,2) NOT NULL,
  min_age INTEGER NOT NULL,
  max_age INTEGER NOT NULL,
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
  type VARCHAR(16) NOT NULL,
  project_leader INTEGER,
  class VARCHAR(8),
  age INTEGER,
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
  user INTEGER NOT NULL,
  PRIMARY KEY(project,user),
  FOREIGN KEY (project)
    REFERENCES projects(id)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT,
  FOREIGN KEY (user)
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
  DROP TRIGGER IF EXISTS trigger_check_choices_age;
  CREATE TRIGGER trigger_check_choices_age
  BEFORE INSERT ON choices
  FOR EACH ROW
  BEGIN
    SELECT CASE WHEN     (SELECT min_age FROM projects WHERE id = NEW.project) > (SELECT age FROM users WHERE id = NEW.user)
            OR (SELECT max_age FROM projects WHERE id = NEW.project) < (SELECT age FROM users WHERE id = NEW.user) THEN
      RAISE(ABORT, 'Der Nutzer passt nicht in die Altersbegrenzung des Projekts!')
    END;
  END;");

  $db->exec("
  DROP TRIGGER IF EXISTS trigger_update_project_check_choices_age;
  CREATE TRIGGER trigger_update_project_check_choices_age
  BEFORE UPDATE ON projects
  FOR EACH ROW
  BEGIN
    SELECT CASE WHEN (SELECT COUNT(*) FROM choices, users WHERE choices.project = NEW.id AND users.id = choices.user AND (users.age < NEW.min_age OR users.age > NEW.max_age)) > 0 THEN
      RAISE(ABORT, 'Geänderte Altersbegrenzung würde Wahlen ungültig machen!')
    END;
  END;");


  $db->exec("
  DROP TRIGGER IF EXISTS trigger_check_project_leader_voted_own_project;
  CREATE TRIGGER trigger_check_project_leader_voted_own_project BEFORE UPDATE ON users
  FOR EACH ROW
  BEGIN
    SELECT CASE WHEN (SELECT COUNT(*) FROM choices WHERE choices.project = NEW.project_leader AND choices.user = NEW.id) > 0 THEN
      RAISE(ABORT, 'Nutzer kann nicht Projektleiter in einem Projekt sein, das er gewählt hat!')
    END;
  END;

  DROP TRIGGER IF EXISTS trigger_check_project_leader_choices;
  CREATE TRIGGER trigger_check_project_leader_choices BEFORE INSERT ON choices
  FOR EACH ROW
  BEGIN
    SELECT CASE WHEN (SELECT COUNT(*) FROM users WHERE users.project_leader = NEW.project AND users.id = NEW.user) > 0 THEN
      RAISE(ABORT, 'Nutzer kann Projekt nicht wählen, in dem er Projektleiter ist!')
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