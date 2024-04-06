<?php

require_once 'db.php';
$lib = new Database();
$db = $lib->getConnection();

$teams = $db->query('SELECT * FROM teams ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);

$last_donations = $db->query('SELECT *, (SELECT name FROM teams WHERE id = donations.team_id) as team, (SELECT logo FROM teams WHERE id = donations.team_id) as team_logo FROM donations ORDER BY id DESC LIMIT 3')->fetchAll(PDO::FETCH_ASSOC);

$top_teams = $db->query('SELECT t.*, COALESCE(SUM(tm.amount), 0) AS total_amount
FROM teams t
LEFT JOIN donations tm ON t.id = tm.team_id
GROUP BY t.id;')->fetchAll(PDO::FETCH_ASSOC);

function convertDate($timestamp) {
  $datetime = new DateTime($timestamp);
  $now = new DateTime();
  $diff = $now->diff($datetime);
  if ($diff->d == 0) {
    if ($diff->h == 0) {
        if ($diff->i < 1) {
            return 'только что';
        } else {
            return $diff->i . ' минут назад';
        }
    } else {
        return $diff->h . ' часов назад';
    }
} elseif ($diff->d == 1 && $diff->h < 12) {
    return 'вчера';
} else {
    return $datetime->format('d.m.Y в H:i');
  }
}

if ($last_donations) {
  $i = 0;
  foreach ($last_donations as $item) {
    $last_donations[$i]['date'] = convertDate($item['created_at']);
    $i++;
  }
} else {
  $last_donations = [];
}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8' />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Football</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

  <link href='/dist/css/global.css' rel='stylesheet' />
</head>
<body>
  <header>
    <div class='container'>
      <div class='logo'>LOGO</div>
      <nav>
        <div class='item scroll-link' href='#about'>О сервисе</div>
        <div class='item scroll-link' href='#last'>Последние пожертвования</div>
        <div class='item scroll-link' href='#rating'>Рейтинг</div>
        <div class='last scroll-link' href='#donate'>
          <span>Пожертвовать</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M352.92 80C288 80 256 144 256 144s-32-64-96.92-64c-52.76 0-94.54 44.14-95.08 96.81-1.1 109.33 86.73 187.08 183 252.42a16 16 0 0018 0c96.26-65.34 184.09-143.09 183-252.42-.54-52.67-42.32-96.81-95.08-96.81z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
        </div>
      </nav>
    </div>  
  </header>

  <div class='wrapper'>

    <section class='first container'>
      <div class='subtitle'>Займись благотворительностью</div>
      <div class='title'>
        <div class='top'>Поддержи</div>
        <div class='bottom'>Любимую Команду</div>
      </div>
      <div class='end'>Выбрать команду</div>
    </section>

    <section class='about container' id='about'>
      <div class='title'>О сервисе</div>
      <div class='text'>Есть много вариантов Lorem Ipsum, но большинство из них имеет не всегда приемлемые модификации, например, юмористические вставки или слова, которые даже отдалённо не напоминают латынь. Если вам нужен Lorem Ipsum для серьёзного проекта, вы наверняка не хотите какой-нибудь шутки, скрытой в середине абзаца.</div>
    </section>

    <section class='second container'>
      <div class='item'>
        <div class='title'>Why do we use it?</div>
        <div class='description'>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</div>
      </div>
      <div class='item'>
        <div class='title'>Why do we use it?</div>
        <div class='description'>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</div>
      </div>
      <div class='item'>
        <div class='title'>Why do we use it?</div>
        <div class='description'>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,</div>
      </div>
    </section>

    <section class='donate' id='donate'>
      <div class='container'>
        <div class='title'>Пожертвовать</div>
        <form class='form' method='POST' action='payment.php'>
          <select name='team'>
            <?php
            $i = 0;
            foreach ($teams as $team) : ?>
            <option <?php ($i == 0) ? 'selected' : '' ?> value='<?php echo $team['id']; ?>'><?php echo $team['name']; ?></option>
            <?php
            $i++;
            endforeach; ?>
          </select>
          <input name='amount'  type='number' placeholder='Сумма в ₽' required />
          <input name='name'    type='text'   placeholder='Имя' required />
          <input name='message' type='text'   placeholder='Сообщение' required />
          <button>Отправить</button>
        </form>
      </div>
    </section>

    <section class='last-donations container' id='last'>
      <div class='title'>Последние пожертвования</div>
      <div class='list'>

        <?php
        foreach ($last_donations as $donation) :
        ?>

        <div class='item'>
          <div class='top'>
            <img src='/uploads/<?php echo $donation['team_logo']; ?>' />
            <div class='name'><?php echo $donation['team']; ?></div>
          </div>
          <div class='message'><?php echo $donation['message']; ?></div>
          <div class='bottom'>
            <div class='time'><?php echo $donation['date']; ?></div>
            <div class='owner'><?php echo $donation['name']; ?></div>
          </div>
        </div>
        
        <?php endforeach; ?>

      </div>
    </section>

    <section class='rating container' id='rating'>
      <div class='title'>Рейтинг команд</div>

      <div class='list'>

        <div class='row'>
          <div class='top'>Место</div>
          <div class='team'>Команда</div>
          <div class='amount'>Сумма пожертвований</div>
        </div>

        <?php 
        $i = 1;
        foreach ($top_teams as $team) : ?>
        
        <div class='row'>
          <div class='top'>#<?php echo $i; ?></div>
          <div class='team'>
            <img src='uploads/<?php echo $team['logo']; ?>' />
            <div class='name'><?php echo $team['name']; ?></div>
          </div>
          <div class='amount'><?php echo $team['total_amount']; ?>₽</div>
        </div>

        <?php
        $i++;
        endforeach; ?>

      </div>
    </section>
    
  </div>

  <footer>
    <div class='container'>
      <div class='copyright'>&copy; lovemyteam.ru</div>
      <div class='socials'>
        <a href='#' target='_blank'><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M349.33 69.33a93.62 93.62 0 0193.34 93.34v186.66a93.62 93.62 0 01-93.34 93.34H162.67a93.62 93.62 0 01-93.34-93.34V162.67a93.62 93.62 0 0193.34-93.34h186.66m0-37.33H162.67C90.8 32 32 90.8 32 162.67v186.66C32 421.2 90.8 480 162.67 480h186.66C421.2 480 480 421.2 480 349.33V162.67C480 90.8 421.2 32 349.33 32z"/><path d="M377.33 162.67a28 28 0 1128-28 27.94 27.94 0 01-28 28zM256 181.33A74.67 74.67 0 11181.33 256 74.75 74.75 0 01256 181.33m0-37.33a112 112 0 10112 112 112 112 0 00-112-112z"/></svg></a>
        <a href='#' target='_blank'><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M414.73 97.1A222.14 222.14 0 00256.94 32C134 32 33.92 131.58 33.87 254a220.61 220.61 0 0029.78 111L32 480l118.25-30.87a223.63 223.63 0 00106.6 27h.09c122.93 0 223-99.59 223.06-222A220.18 220.18 0 00414.73 97.1zM256.94 438.66h-.08a185.75 185.75 0 01-94.36-25.72l-6.77-4-70.17 18.32 18.73-68.09-4.41-7A183.46 183.46 0 0171.53 254c0-101.73 83.21-184.5 185.48-184.5a185 185 0 01185.33 184.64c-.04 101.74-83.21 184.52-185.4 184.52zm101.69-138.19c-5.57-2.78-33-16.2-38.08-18.05s-8.83-2.78-12.54 2.78-14.4 18-17.65 21.75-6.5 4.16-12.07 1.38-23.54-8.63-44.83-27.53c-16.57-14.71-27.75-32.87-31-38.42s-.35-8.56 2.44-11.32c2.51-2.49 5.57-6.48 8.36-9.72s3.72-5.56 5.57-9.26.93-6.94-.46-9.71-12.54-30.08-17.18-41.19c-4.53-10.82-9.12-9.35-12.54-9.52-3.25-.16-7-.2-10.69-.2a20.53 20.53 0 00-14.86 6.94c-5.11 5.56-19.51 19-19.51 46.28s20 53.68 22.76 57.38 39.3 59.73 95.21 83.76a323.11 323.11 0 0031.78 11.68c13.35 4.22 25.5 3.63 35.1 2.2 10.71-1.59 33-13.42 37.63-26.38s4.64-24.06 3.25-26.37-5.11-3.71-10.69-6.48z" fill-rule="evenodd"/></svg></a>
        <a href='#' target='_black'><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M496 109.5a201.8 201.8 0 01-56.55 15.3 97.51 97.51 0 0043.33-53.6 197.74 197.74 0 01-62.56 23.5A99.14 99.14 0 00348.31 64c-54.42 0-98.46 43.4-98.46 96.9a93.21 93.21 0 002.54 22.1 280.7 280.7 0 01-203-101.3A95.69 95.69 0 0036 130.4c0 33.6 17.53 63.3 44 80.7A97.5 97.5 0 0135.22 199v1.2c0 47 34 86.1 79 95a100.76 100.76 0 01-25.94 3.4 94.38 94.38 0 01-18.51-1.8c12.51 38.5 48.92 66.5 92.05 67.3A199.59 199.59 0 0139.5 405.6a203 203 0 01-23.5-1.4A278.68 278.68 0 00166.74 448c181.36 0 280.44-147.7 280.44-275.8 0-4.2-.11-8.4-.31-12.5A198.48 198.48 0 00496 109.5z"/></svg></a>
      </div>
    </div>
  </footer>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha512-7eHRwcbYkK4d9g/6tD/mhkf++eoTHwpNM9woBxtPUBWm67zeAfFC+HrdoE2GanKeocly/VxeLvIqwvCdk7qScg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js" integrity="sha512-1PKqXBz2ju2JcAerHKL0ldg0PT/1vr3LghYAtc59+9xy8e19QEtaNUyt1gprouyWnpOPqNJjL4gXMRMEpHYyLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script type='text/javascript'>
  const navScrollToListener = (e) => {
    gsap.to(window, {
      duration: 1,
      scrollTo: '',
      scrollTo: { y: e.currentTarget.getAttribute('href'), offsetY: 100 },
      ease: "Power1.easeInOut"
    });
  };

  const navLinks = document.querySelectorAll('.scroll-link');
  navLinks.forEach((navLink) => navLink.addEventListener('click', navScrollToListener));
  </script>
</body>
</html>