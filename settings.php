<?php
require_once __DIR__ . '/record.php';

class Settings extends Record {
  public $election_running;

  public function __construct($data = null) {
    if (is_array($data)) {
      $this->update($data);
    }
  }

  public function update($data) {
    $this->election_running = $data['election_running'] ?? $this->election_running;
  }

  public function getValidationErrors() {
    $validation_errors = array();
    if (blank($this->election_running)) {
      array_push($validation_errors, "\"Wahl laufend\" fehlt!");
    }
    return $validation_errors;
  }

  public static function get() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM settings LIMIT 1;');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Settings');
  }
}
?>
