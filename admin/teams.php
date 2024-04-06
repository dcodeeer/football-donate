<?php
session_start();

session_start();
if (empty($_SESSION['admin'])) {
  header('Location: /admin/login.php');
  die();
}

require_once '../db.php';
$lib = new Database();
$db = $lib->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_FILES["file"]) && isset($_POST['name'])) {
    $name = $_POST['name'];
    $file_name = $_FILES["file"]["name"];
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

    $new_file_name = uniqid() . '.' . $file_extension;

    $target_dir = "../uploads/";
    $target_file = $target_dir . $new_file_name;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
      $stmt = $db->prepare('INSERT INTO teams (name, logo) VALUES (:name, :logo);');
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':logo', $new_file_name, PDO::PARAM_STR);
      $stmt->execute();
    } else {
      echo "Произошла ошибка при добавлении команды.";
    }
  }
}


$teams = $db->query('SELECT * FROM teams ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
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
  <link href='/dist/css/admin-teams.css' rel='stylesheet' />
</head>
<body>
  <header>
    <div class='container'>
      <div class='title'>Панель управления</div>
    </div>
  </header>
  <content>
    <div class='container'>
      <div class='first'>
        <div class='title'>Добавить команду</div>
        <div class='box'>
          <form enctype="multipart/form-data" method='POST' class='form' autocomplete='off'>
            <input id='file' type='file' name='file' accept="image/jpeg, image/png, image/webp" required />

            <label for='file'>
              <div class='select'>Выбрать файл</div>
              <div class='selected'>Файл выбран</div>
            </label>
            
            <input type='text' name='name' placeholder='Имя команды' required/>
            <button>Загрузить</button>
          </form>
        </div>
      </div>
      <div class='second'>
        <div class='list'>
          <?php
          if (count($teams) == 0) echo 'Список пуст.';
          foreach ($teams as $team) : ?>
          <a class='row' href='/admin/delete-team.php?id=<?php echo $team['id']; ?>'>
            <img src='/uploads/<?php echo $team['logo']; ?>' />
            <div class='name'><?php echo $team['name']; ?></div>
            <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M112 112l20 320c.95 18.49 14.4 32 32 32h184c17.67 0 30.87-13.51 32-32l20-320" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 112h352"/><path d="M192 112V72h0a23.93 23.93 0 0124-24h80a23.93 23.93 0 0124 24h0v40M256 176v224M184 176l8 224M328 176l-8 224" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </content>
</body>
</html>