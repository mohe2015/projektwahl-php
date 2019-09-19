<?php
/*
projektwahl-php - manage project selection for many people
Copyright (C) 2019 Moritz Hedtke <Moritz.Hedtke@t-online.de>

projektwahl-php is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

projektwahl-php is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with projektwahl-php.  If not, see <https://www.gnu.org/licenses/>.
*/
require_once __DIR__ . '/record.php';

class User extends Record {
  public $id;
  public $name;
  public $password;
  public $type;
  public $password_changed;

  protected static $insert_stmt = null;
  protected static $update_stmt = null;

  protected static function getInsertStatement() {
    global $db;
    if (null === self::$insert_stmt) {
        self::$insert_stmt = $db->prepare('INSERT INTO users (name, password, type, password_changed, project_leader, class, grade, away, in_project) VALUES (:name, :password, :type, :password_changed, :project_leader, :class, :grade, :away, :in_project)');
    }
    return self::$insert_stmt;
  }

  protected static function getUpdateStatement() {
    global $db;
    if (null === self::$update_stmt) {
      self::$update_stmt = $db->prepare('UPDATE users SET name = :name, password = :password, type = :type, password_changed = :password_changed, project_leader = :project_leader, class = :class, grade = :grade, away = :away, in_project = :in_project WHERE id = :id');
    }
    return self::$update_stmt;
  }

  public function __construct($data = null) {
    if (is_array($data)) {
      $this->update($data);
    }
  }

  public function update($data) {
    $this->name = $data['name'] ?? $this->name;
    $this->password = array_key_exists('password', $data) ? $data['password'] : $this->password;
    $this->type = $data['type'] ?? $this->type;
    $this->password_changed = $data['password_changed'] ?? $this->password_changed ?? false;
  }

  public function getValidationErrors() {
    $validation_errors = array();
    if (empty($this->name)) {
      array_push($validation_errors, "Name fehlt!");
    }
    if (empty($this->password)) {
      //array_push($validation_errors, "Passwort fehlt!");
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
        'password_changed' => $this->password_changed ? 1 : 0,
        'project_leader' => $this->project_leader,
        'class' => $this->class,
        'grade' => $this->grade,
        'away' => $this->away ? 1 : 0,
        'in_project' => $this->in_project
      ));
      $this->id = $db->lastInsertId();
      apcu_store("user-$this->id", $this);
      apcu_delete(['users', 'teachers', 'students']); // TODO alternatively update vars
      return $this;
    } else {
      $stmt = self::getUpdateStatement();
      $stmt->execute(array(
        'id' => $this->id,
        'name' => $this->name,
        'password' => $this->password,
        'type' => $this->type,
        'password_changed' => $this->password_changed ? 1 : 0,
        'project_leader' => $this->project_leader,
        'class' => $this->class,
        'grade' => $this->grade,
        'away' => $this->away ? 1 : 0,
        'in_project' => $this->in_project
      ));
      apcu_store("user-$this->id", $this);
      apcu_delete(['users', 'teachers', 'students']); // TODO alternatively update vars
      return $this;
    }
  }

  // TODO FIXME if somebody edits and deletes at the same time there could be a race condition. I don't know of a way to fix this.

  public function delete() {
    global $db;
    $stmt = $db->prepare('DELETE FROM users WHERE id = :id;');
    $stmt->execute(array(
      'id' => $this->id
    ));
    apcu_delete(["user-$this->id", 'users', 'teachers', 'students']); // TODO alternatively update vars
  }
}

class Users {
  public function find($id) {
    // TODO combine user, teacher and student cache
    $result = apcu_fetch("user-$id");
    if ($result) {
      return $result;
    }
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(array('id' => $id));
    $result = $stmt->fetchObject('User');
    apcu_add("user-$id", $result);
    return $result;
  }

  public function findByName($name) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE name = :name");
    $stmt->execute(array('name' => $name));
    $result = $stmt->fetchObject('User');
    return $result;
  }

  public function all() {
    $result = apcu_fetch('users');
    if ($result) {
      return $result;
    }
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE type = 'teacher' OR type = 'student';");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    apcu_add("users", $result);
    return $result;
  }
}
?>
