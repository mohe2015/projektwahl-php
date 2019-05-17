<?php
require_once 'record.php';

class User extends Record {
  public $id;
  public $name;
  public $password;
  public $type;

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
}
?>
