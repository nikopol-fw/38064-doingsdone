<?php

require_once 'utils/functions.php';
require_once 'utils/config.php';
require_once 'utils/db_config.php';
require_once 'mysql_helper.php';


if ($config['site_enable'] === false) {
  echo 'Сайт на технических работах. Зайдите позже.';


} else {
  $db_conf = db_connect($db_host, $db_user, $db_pass, $db_name);


  if ($_SERVER['REQUSET_METHOD'] === 'POST') {
    $user = $_POST['user'];
    $error_post = [];


    $required = ['email', 'password', 'name'];
    define('ERRORS_REG', [
      'email'    => 'Укажите E-mail для регистрации',
      'password' => 'Задайте пароль для авторизации',
      'name'     => 'Укажите имя',
      'email_invalid' => 'Укажите валидный E-mail адрес'
    ]);

    foreach ($required as $field) {
      // Проверка обязательных полей
      if (empty($user[$field])) {
        $error_post[$field] = ERRORS_REG[$field];

        // Проверка полей на кастомные условия
      } else {
        switch ($field) {
          case 'email':
            if (!filter_var($user[$field], FILTER_VALIDATE_EMAIL)) {
              $error_post[$field] = ERRORS_REG['email_invalid'];
            } else {
              $safe_email = mysqli_real_escape_string($db_conf, $user[$field]);
              $sql = "SELECT COUNT(email) AS count\n"
                   . "  FROM user\n"
                   . " WHERE email = '$safe_email';\n";
              $result = mysqli_query($db_conf, $sql);

              if (!$result) {
                exit(1);
              }

              $count = mysqli_fetch_field($result);
              var_dump($count);
              if ($count !== '0') {

              }
            }
            break;

          default:
            break;
        }
      }
    }

    if (count($error_post)) {
      $content = render_template('form_register.php', [
        'errors' => $error_post,
        'user'   => $user
      ]);

      // Нет ошибок при отправке формы
    } else {

    }

    // Сценарий вызван без метода POST
  } else {
    $content = render_template('form_register.php', [

    ]);
  }


  $template = render_template('layout_guest.php', [
    'page_title' => $config['site_name'] . ' | Регистрация',
    'content' => $content
  ]);

  echo $template;
}
