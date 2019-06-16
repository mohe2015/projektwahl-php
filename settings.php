<?php
require_once __DIR__ . '/record.php';

class Settings extends Record {
  public $election_running;

  protected static $update_stmt = null;

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
    if (!isset($this->election_running)) {
      array_push($validation_errors, "\"Wahl laufend\" fehlt!");
    }
    return $validation_errors;
  }

  protected static function getUpdateStatement() {
    global $db;
    if (null === self::$update_stmt) {
      self::$update_stmt = $db->prepare('UPDATE settings SET election_running = :election_running');
    }
    return self::$update_stmt;
  }

  // stale data should be fine (for a few milliseconds)

  public function save() {
    global $db;
    $this->validate();
    $stmt = self::getUpdateStatement();
    $stmt->execute(array(
      'election_running' => $this->election_running ? 1 : 0,
    ));
    apcu_store('settings', $this);
    return $this;
  }

  public static function get() {
    $result = apcu_fetch('settings');
    if ($result) {
      return $result;
    }
    global $db;
    $stmt = $db->prepare('SELECT * FROM settings LIMIT 1;');
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Settings');
    $stmt->execute();
    $result = $stmt->fetch();
    apcu_add('settings', $result);
    return $result;
  }
}
?>
