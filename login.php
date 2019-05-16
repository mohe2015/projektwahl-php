<?php
require_once 'head.php';
if (isset($_SESSION['name'])) {
  header("Location: /");
  die();
}
if (!empty($_POST)) {
  $name = $_POST['name'];
  $password = $_POST['password'];
  $stmt = $db->prepare('SELECT * FROM users WHERE name = :name');
  $stmt->execute(array('name' => $name));
  $result = $stmt->fetchAll();
  print_r($result[0]["password"]);
  if (password_verify($password, $result[0]["password"])) {
    if (password_needs_rehash($result[0]["password"], PASSWORD_DEFAULT, $options)) {
      die("TODO: needs rehashing");
    }
    session_regenerate_id(true);
    $_SESSION['name'] = $result[0]["name"];
    header("Location: /");
    die();
  } else {
    die("invalid password");
  }
}
?>
<form method="post">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" />
  <label for="password">Passwort:</label>
  <input type="password" id="password" name="password" />
  <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
  <button type="submit">Anmelden</button>
</form>
