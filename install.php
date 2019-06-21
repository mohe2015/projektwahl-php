<?php
$allowed_users = array();
require_once __DIR__ . '/head.php';

try {
  $stmt = $db->query("DO $$ BEGIN
  CREATE TYPE type AS ENUM ('student', 'teacher', 'admin');
  EXCEPTION
    WHEN duplicate_object THEN null;
  END $$;");

  $stmt = $db->query("CREATE TABLE IF NOT EXISTS projects (
  id SERIAL PRIMARY KEY,
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

  $stmt = $db->query("CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  name VARCHAR(64) UNIQUE NOT NULL,
  password VARCHAR(255),
  type type NOT NULL,
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

  $stmt = $db->query("CREATE TABLE IF NOT EXISTS settings (
  id SERIAL PRIMARY KEY,
  election_running BOOLEAN NOT NULL
  );");
  $stmt->closeCursor();

  $db->exec("CREATE OR REPLACE FUNCTION check_choices_grade() RETURNS TRIGGER AS
  $$
  BEGIN
  IF (SELECT min_grade FROM projects WHERE id = NEW.project) > (SELECT grade FROM users WHERE id = NEW.student) OR (SELECT max_grade FROM projects WHERE id = NEW.project) < (SELECT grade FROM users WHERE id = NEW.student) THEN
  RAISE EXCEPTION 'Der Schüler passt nicht in die Altersbegrenzung des Projekts!';
  END IF;
  RETURN NEW;
  END;
  $$
  LANGUAGE plpgsql;

  DROP TRIGGER IF EXISTS trigger_check_choices_grade ON choices;
  CREATE TRIGGER trigger_check_choices_grade
  BEFORE INSERT ON choices
  FOR EACH ROW EXECUTE FUNCTION check_choices_grade();

  CREATE OR REPLACE FUNCTION update_project_check_choices_grade() RETURNS TRIGGER AS
  $$
  BEGIN
  IF (SELECT COUNT(*) FROM choices, users WHERE choices.project = NEW.id AND users.id = choices.student AND (users.grade < NEW.min_grade OR users.grade > NEW.max_grade)) > 0 THEN
  RAISE EXCEPTION 'Geänderte Altersbegrenzung würde Wahlen ungültig machen!';
  END IF;
  RETURN NEW;
  END;
  $$
  LANGUAGE plpgsql;

  DROP TRIGGER IF EXISTS trigger_update_project_check_choices_grade ON projects;
  CREATE TRIGGER trigger_update_project_check_choices_grade
  BEFORE UPDATE ON projects
  FOR EACH ROW EXECUTE FUNCTION update_project_check_choices_grade();");

  $stmt = $db->query("INSERT INTO settings (id, election_running) VALUES (1, false) ON CONFLICT DO NOTHING;");
  $stmt->closeCursor();

  $stmt = $db->prepare("INSERT INTO users (name, password, type) VALUES (:name, :password, 'admin') ON CONFLICT DO NOTHING");
  $stmt->execute(array('name' => 'Admin', 'password' => password_hash("password", PASSWORD_DEFAULT, $options)));
} catch (PDOException $e) {
    print "Fehler bei der Installation: " . $e . "<br/>";
    print 'Vielleicht hast du bereits alles installiert? Versuchs mal mit der <a href="/">Startseite</a>';
    die();
}

echo "Installation erfolgreich! Der Standard-Account lautet:<br />Nutzername: Admin<br />Passwort: password";
echo '<br /><a href="/">Zur Startseite</a>';
?>
