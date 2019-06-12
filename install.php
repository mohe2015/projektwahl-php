<?php
$allowed_users = array();
require_once __DIR__ . '/head.php';

try {
  $stmt = $db->query("CREATE TYPE type AS ENUM ('student', 'teacher', 'admin');");

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
  password VARCHAR(255) NOT NULL,
  type type NOT NULL,
  project_leader INTEGER,
  class VARCHAR(8),
  grade INTEGER,
  away BOOLEAN,
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
  election_running BOOLEAN NOT NULL
  );");
  $stmt->closeCursor();

  $stmt = $db->query("INSERT INTO settings (election_running) VALUES (false);");
  $stmt->closeCursor();

  $stmt = $db->prepare("INSERT INTO users (name, password, type) VALUES (:name, :password, 'admin')");
  $stmt->execute(array('name' => 'Admin', 'password' => password_hash("password", PASSWORD_DEFAULT, $options)));
} catch (PDOException $e) {
    print "Fehler bei der Installation: " . $e . "<br/>";
    print 'Vielleicht hast du bereits alles installiert? Versuchs mal mit der <a href="/">Startseite</a>';
    die();
}

echo "Installation erfolgreich! Der Standard-Account lautet:<br />Nutzername: Admin<br />Passwort: password";
echo '<br /><a href="/">Zur Startseite</a>';
?>
