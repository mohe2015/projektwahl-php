<?php
require_once 'vendor/autoload.php';
use ZxcvbnPhp\Zxcvbn;

$userData = [
  'Marco',
  'marco@example.com'
];

$zxcvbn = new Zxcvbn();
$strength = $zxcvbn->passwordStrength($_SERVER['QUERY_STRING'], $userData);
echo json_encode($strength);
?>
