<?php

require_once 'utils/functions.php';
require_once 'utils/config.php';
require_once 'utils/db_config.php';
require_once 'mysql_helper.php';


if ($config['site_enable'] === false) {
  echo 'Сайт на технических работах. Зайдите позже.';
  exit(0);
}


session_start();


// Если пользователь уже авторизован
if (isset($_SESSION['user'])) {
  header('Location: index.php');
  exit(0);
}
// End


$db_conf = db_connect($db_host, $db_user, $db_pass, $db_name);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login      = $_POST['login'];
  $error_post = [];
  $user       = null;

  $required = ['email', 'password'];
  define('ERRORS_LOGIN', [
    'email'          => 'Укажите E-mail вашей учетной записи',
    'password'       => 'Введите пароль для прохождения аутентификации',
    'email_wrong'    => 'Введён неверный E-mail',
    'password_wrong' => 'Указан некорректный пароль'
  ]);

  foreach ($required as $field) {
    // Проверка обязательных полей
    if (empty($login[$field])) {
      $error_post[$field] = ERRORS_LOGIN[$field];

      // Проверка полей на кастомные условия
    } else {
      switch ($field) {
        case 'email':
          $safe_email = mysqli_real_escape_string($db_conf, $login[$field]);
          $sql = "SELECT *\n"
               . "  FROM user\n"
               . " WHERE email = '$safe_email';";
          $result = mysqli_query($db_conf, $sql);

          $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
          if (!$user['email']) {
            $error_post[$field] = ERRORS_LOGIN['email_wrong'];
          }
          break;

        case 'password':
          if (!($user['pass'] && password_verify($login[$field], $user['pass']))) {
            $error_post[$field] = ERRORS_LOGIN['password_wrong'];
          }
          break;

        default:
          break;
      }
    }
  }


  if (count($error_post)) {
    $content = render_template('form_login.php', [
      'errors' => $error_post,
      'login'  => $login
    ]);

    $template = render_template('layout_guest.php', [
      'page_title' => $config['site_name'] . ' | Авторизация',
      'content'    => $content,
    ]);
    echo $template;

    // Нет ошибок при отправке формы
  } else {
    $_SESSION['user'] = $user;
    header('Location: ./');
  }

  exit(0);
}


$content = render_template('form_login.php');

$template = render_template('layout_guest.php', [
  'page_title' => $config['site_name'] . ' | Авторизация',
  'content'    => $content,
]);
echo $template;
