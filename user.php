<?php
require_once __DIR__ . '/record.php';

class User extends Record {
  public $id;
  public $name;
  public $password;
  public $type;

  protected static $insert_stmt = null;

  protected static function getInsertStatement() {
    global $db;
    if (null === self::$insert_stmt) {
        self::$insert_stmt = $db->prepare('INSERT INTO users (name, password, type, project_leader, class, grade, away, in_project) VALUES (:name, :password, :type, :project_leader, :class, :grade, :away, :in_project)');
    }
    return self::$insert_stmt;
  }

  public function __construct($data = null) {
    if (is_array($data)) {
      $this->name = $data['name'];
      $this->password = $data['password'];
      $this->type = $data['type'];
    }
  }

  public function getValidationErrors() {
    $validation_errors = array();
    if (empty($this->name)) {
      array_push($validation_errors, "Name fehlt!");
    }
    if (empty($this->password)) {
      array_push($validation_errors, "Passwort fehlt!");
    }
    if (empty($this->type)) {
      array_push($validation_errors, "Typ fehlt!");
    }
    return $validation_errors;
  }

  public function save() {
    global $db;
    $this->validate();
    if (empty($this->id)) {
      self::getInsertStatement()->execute(array(
        'name' => $this->name,
        'password' => $this->password,
        'type' => $this->type,
        'project_leader' => $this->project_leader,
        'class' => $this->class,
        'grade' => $this->grade,
        'away' => $this->away ? 1 : 0,
        'in_project' => $this->in_project
      ));
      $this->id = $db->lastInsertId();
    } else {
      $stmt = $db->prepare('UPDATE users SET name = :name, password = :password, type = :type, project_leader = :project_leader, class = :class, grade = :grade, away = :away, in_project = :in_project WHERE id = :id');
      $stmt->execute(array(
        'id' => $this->id,
        'name' => $this->name,
        'password' => $this->password,
        'type' => $this->type,
        'project_leader' => $this->project_leader,
        'class' => $this->class,
        'grade' => $this->grade,
        'away' => $this->away ? 1 : 0,
        'in_project' => $this->in_project
      ));
    }
  }

  public function delete() {
    global $db;
    $stmt = $db->prepare('DELETE FROM users WHERE id = :id;');
    $stmt->execute(array(
      'id' => $this->id
    ));
  }
}
?>
