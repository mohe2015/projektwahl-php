<?php
class User {
  public $id;
  public $name;
  public $password;
  public $type;
  public $project_leader;
  public $class;
  public $grade;
  public $away;
  public $in_project;
  public function __construct($data = null) {
    if (is_array($data)) {
      $this->name = $data['name'];
      $this->password = $data['password'];
      $this->type = $data['type'];
      $this->project_leader = $data['project_leader'];
      $this->class = $data['class'];
      $this->grade = $data['grade'];
      $this->away = $data['away'];
      $this->in_project = $data['in_project'];
    }
  }
}
class Users {
  public function find($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute(array('id' => $id));
    return $stmt->fetchObject('User');
  }
  public function all() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM users;');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
  }
  public function validate(\User $user) {
    $validation_errors = array();
    if (empty($user->name)) {
      array_push($validation_errors, "Name fehlt!");
    }
    if (empty($user->password)) {
      array_push($validation_errors, "Passwort fehlt!");
    }
    if (empty($user->type)) {
      array_push($validation_errors, "Typ fehlt!");
    }
    if (empty($user->project_leader)) {
      array_push($validation_errors, "Projektleiter fehlt!");
    }
    if (empty($user->class)) {
      array_push($validation_errors, "Klasse fehlt!");
    }
    if (empty($user->grade)) {
      array_push($validation_errors, "Jahrgang fehlt!");
    }
    if (empty($user->away)) {
      array_push($validation_errors, "Abwesend fehlt!");
    }
    if (empty($user->in_project)) {
      array_push($validation_errors, "Projekt fehlt!");
    }
    if (!empty($validation_errors)) {
      throw new ValidationError(implode("<br>", $validation_errors));
    }
  }
  public function save(\User $user) {
    Users::validate($user);
    global $db;
    if (empty($user->id)) {
      $stmt = $db->prepare('INSERT INTO users (name, password, type, project_leader, class, grade, away, in_project) VALUES (:name, :password, :type, :project_leader, :class, :grade, :away, :in_project)');
      $stmt->execute(array(
        'name' => $user->name,
        'password' => $user->password,
        'type' => $user->type,
        'project_leader' => $user->project_leader,
        'class' => $user->class,
        'grade' => $user->grade,
        'away' => $user->away,
        'in_project' => $user->in_project
      ));
      $user->id = $db->lastInsertId();
    } else {
      $stmt = $db->prepare('UPDATE users SET name = :name, password = :password, type = :type, project_leader = :project_leader, class = :class, grade = :grade, away = :away, in_project = :in_project WHERE id = :id');
      $stmt->execute(array(
        'id' => $user->id,
        'name' => $user->name,
        'password' => $user->password,
        'type' => $user->type,
        'project_leader' => $user->project_leader,
        'class' => $user->class,
        'grade' => $user->grade,
        'away' => $user->away,
        'in_project' => $user->in_project
      ));
    }
  }
}

?>
