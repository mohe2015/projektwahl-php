<?php

class Record {
    public $types = array();

    public $members = array();

    public function __get($item) {
        return $this->members[$item];
    }

    public function __set($item, $value) {
        $this->members[$item] = $value;
    }
}

class User {
    public int $id;
    public string $name;
    public string $password_hash;
    public string $type;
    public bool $password_changed;

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

    public function save() {
        $reflect = new ReflectionClass($this);
        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
        $props_arr = array();
        foreach ($props as $prop) {
            $props_arr[$prop->getName()] = $prop->getValue($this); 
        }
        var_dump($props_arr);
        self::getInsertStatement()->execute($props_arr);
        $this->id = $db->lastInsertId();
    }
}

class Users {
    public function find($id) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(array('id' => $id));
        $result = $stmt->fetchObject('User');
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
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE type = 'teacher' OR type = 'student';");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
        return $result;
    }
}

?>