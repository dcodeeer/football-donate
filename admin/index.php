<?php
session_start();
if (empty($_SESSION['admin'])) {
  header('Location: /admin/login.php');
  die();
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
  <content>
    <div class='container'>
      <a class='block' href='/admin/teams.php'>
        <div class='icon'>
          <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M402 168c-2.93 40.67-33.1 72-66 72s-63.12-31.32-66-72c-3-42.31 26.37-72 66-72s69 30.46 66 72z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M336 304c-65.17 0-127.84 32.37-143.54 95.41-2.08 8.34 3.15 16.59 11.72 16.59h263.65c8.57 0 13.77-8.25 11.72-16.59C463.85 335.36 401.18 304 336 304z" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"/><path d="M200 185.94c-2.34 32.48-26.72 58.06-53 58.06s-50.7-25.57-53-58.06C91.61 152.15 115.34 128 147 128s55.39 24.77 53 57.94z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path d="M206 306c-18.05-8.27-37.93-11.45-59-11.45-52 0-102.1 25.85-114.65 76.2-1.65 6.66 2.53 13.25 9.37 13.25H154" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32"/></svg></div>
        <div class='name'>Команды</div>
      </a>
      
      <a class='block' href='/admin/donations.php'>
        <div class='icon'><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><rect x="48" y="144" width="416" height="288" rx="48" ry="48" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M411.36 144v-30A50 50 0 00352 64.9L88.64 109.85A50 50 0 0048 159v49" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M368 320a32 32 0 1132-32 32 32 0 01-32 32z"/></svg></div>
        <div class='name'>Пожертвования</div>
      </a>
    </div>
  </content>
</body>
</html>