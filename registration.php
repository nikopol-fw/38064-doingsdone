<?php

require_once 'utils/functions.php';
require_once 'utils/config.php';
require_once 'utils/db_config.php';
require_once 'mysql_helper.php';


if ($config['site_enable'] === false) {
  echo 'Сайт на технических работах. Зайдите позже.';


} else {
  $db_conf = db_connect($db_host, $db_user, $db_pass, $db_name);


  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user       = $_POST['user'];
    $error_post = [];

    $required = ['email', 'password', 'name'];
    define('ERRORS_REG', [
      'email'          => 'Укажите E-mail для регистрации',
      'password'       => 'Задайте пароль для авторизации',
      'name'           => 'Укажите имя',
      'email_invalid'  => 'Укажите валидный E-mail адрес',
      'email_occupied' => 'Пользователь с таким E-mail уже зарегистрирован',
      'password_short' => 'Короткий пароль. Укажите не менее 8 символов'
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

              $count = mysqli_fetch_assoc($result);
              if ($count['count'] !== '0') {
                $error_post[$field] = ERRORS_REG['email_occupied'];
              }
            }
            break;

          case 'password':
            if (strlen($user[$field]) < 8) {
              $error_post[$field] = ERRORS_REG['password_short'];
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
      $password_hash = password_hash($user['password'], PASSWORD_DEFAULT);
      $sql = "INSERT INTO user (name, email, pass, date_reg)\n"
           . "     VALUES (?, ?, ?, CURRENT_TIMESTAMP);";
      $stmt = db_get_prepare_stmt($db_conf, $sql, [
        $user['name'],
        $user['email'],
        $password_hash
      ]);

      if (mysqli_stmt_execute($stmt)) {
        header('Location: index.php');
        exit(0);
      }

      $error = mysqli_error($db_conf);
      $content = '<p>Регистрация неудалась. Ошибка MySQL: ' . $error .'</p>';
    }

    // Сценарий вызван без метода POST
  } else {
    $content = render_template('form_register.php');
  }


  $template = render_template('layout_guest.php', [
    'page_title' => $config['site_name'] . ' | Регистрация',
    'content'    => $content
  ]);

  echo $template;
}
