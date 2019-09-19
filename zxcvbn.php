<?php
$allowed_users = array("student", "teacher", "admin");
require_once __DIR__ . '/header.php';

$user = end($_SESSION['users']);

require_once __DIR__ . '/vendor/autoload.php';
use ZxcvbnPhp\Zxcvbn;

$userData = explode(" ", $user->name);
array_push($userData, $user->name);

$zxcvbn = new Zxcvbn();
$strength = $zxcvbn->passwordStrength($_POST['new_password'], $userData);
echo json_encode($strength);
?>
