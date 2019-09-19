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
