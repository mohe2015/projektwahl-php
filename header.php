<?php
require_once 'project.php';
require_once 'user.php';
session_start();
if (!empty($_POST)) {
  if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die("CSRF token not valid");
  }
}
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
require_once 'config.php';
try {
    $db = new PDO($database['url'], $database['username'], $database['password'], array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ));
/*
    $stmt = $db->query("CREATE TABLE IF NOT EXISTS projects (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL ,
    title VARCHAR(256) NOT NULL,
    info VARCHAR(4096) NOT NULL,
    place VARCHAR(256) NOT NULL,
    costs DECIMAL(4,2) NOT NULL,
    min_grade TINYINT NOT NULL,
    max_grade TINYINT NOT NULL,
    min_participants TINYINT NOT NULL,
    max_participants TINYINT NOT NULL,
    presentation_type VARCHAR(512) NOT NULL,
    requirements VARCHAR(1024) NOT NULL,
    random_assignments BOOLEAN NOT NULL
    );");
    $stmt->closeCursor();

    $stmt = $db->query("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(64) NOT NULL,
    password VARCHAR(255) NOT NULL,
    type ENUM('student', 'teacher', 'admin') NOT NULL,
    project_leader INTEGER,
    class VARCHAR(8),
    grade TINYINT UNSIGNED,
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
    rank TINYINT NOT NULL,
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
*/
    //$stmt = $db->prepare('INSERT INTO users (name, password, type) VALUES (:name, :password, "admin")');
    //$stmt->execute(array('name' => 'admin', 'password' => password_hash("admin", PASSWORD_DEFAULT, $options)));
} catch (PDOException $e) {
    print "Error!: " . $e . "<br/>";
    die();
}
?>
