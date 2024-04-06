<?php

session_start();

require_once '../db.php';
$lib = new Database();
$db = $lib->getConnection();

if (isset($_SESSION['admin'])) {
  header('Location: /admin');
  die();
}

$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $login = $_POST['login'];
  $password = $_POST['password'];

  if (isset($login) && isset($password)) {
    $stmt = $db->prepare('SELECT * FROM admins WHERE login = :login AND password = :password');
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $_SESSION['admin'] = true;
      header('Location: /admin');
    } else {
      $error = true;
    }
  } else {
    $error = true;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8' />
  <title>Football</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

  
  <link href='/dist/css/global.css' rel='stylesheet' />
  <link href='/dist/css/admin.css' rel='stylesheet' />
</head>
<body>
  <header>
    <div class='container'>
      <div class='title'>Панель управления</div>
    </div>
  </header>
  <div class='login-box'>
    <form method='POST' autocomplete='off'>
      <div class='title'>Войдите</div>
      <div class='error'><?php echo $error ? 'неправильные данные' : ''?></div>
      <input type='text' name='login' placeholder='Логин' required />
      <input type='password' name='password' placeholder='Пароль' required />
      <button>Войти</button>
    </form>
  </div>
</body>
</html>