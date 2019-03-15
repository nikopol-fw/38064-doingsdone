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

$is_auth   = false;
$user_name = '';

if (isset($_SESSION['user'])) {
  $is_auth   = true;
  $user_name = $_SESSION['user']['name'];
} else {
  header('Location: ./');
  exit(0);
}


$db_conf = db_connect($db_host, $db_user, $db_pass, $db_name);


// Получаем список проектов $projects и количество задач для каждого проекта
$projects = '';
$user_id = $_SESSION['user']['user_id'];
$sql = "SELECT p.project_id, project_name, COUNT(t.project_id) AS project_count\n"
  . "     FROM project AS p\n"
  . "LEFT JOIN task AS t\n"
  . "       ON p.project_id = t.project_id\n"
  . "    WHERE p.user_author = 1\n"
  . " GROUP BY project_name\n"
  . " ORDER BY p.project_id ASC;";

$result = mysqli_query($db_conf, $sql);
if (!$result) {

} else {
  $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
// End Получаем список проектов $projects и количество задач для каждого проекта


// Сценарий вызван с методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $project = $_POST['project'];
  $errors_post = [];

  $required = ['name'];
  define('ERRORS_PROJECT', [
    'name'          => 'Укажите название проекта',
    'name_occupied' => 'Проект с таким название уже существует'
  ]);

  foreach ($required as $field) {
    // Проверка обязательных полей
    if (empty($project[$field])) {
      $errors_post[$field] = ERRORS_PROJECT[$field];

      // Проверка полей на кастомные условия
    } else {
      switch ($field) {
        case 'name':
          $safe_name = mysqli_real_escape_string($db_conf, $project[$field]);
          $user_id = $_SESSION['user']['user_id'];
          $sql = "SELECT COUNT(project_name) AS count\n"
               . "  FROM project\n"
               . " WHERE user_author = '$user_id'\n"
               . "   AND project_name = '$safe_name';";
          $result = mysqli_query($db_conf, $sql);

          if (!$result) {
            exit(1);
          }

          $count = mysqli_fetch_assoc($result);
          if ($count['count'] !== '0') {
            $errors_post[$field] = ERRORS_PROJECT['name_occupied'];
          }
          break;

        default:
          break;
      }
    }
  }

  if (count($errors_post)) {
    $content = render_template('form_project.php', [
      'errors' => $errors_post,
      'project'   => $project
    ]);

  // Нет ошибок при отправке формы
  } else {
    $user_id = $_SESSION['user']['user_id'];
    $sql = "INSERT INTO project (project_name, user_author) "
         . "     VALUES ('?', '$user_id');";
    $stmt = db_get_prepare_stmt($db_conf, $sql, [
      $project['name']
    ]);
    mysqli_stmt_execute($stmt);

    header('Location: ./');
    exit(0);
  }
} else {
  $content = render_template('form_project.php', [
    'projects' => $projects,
  ]);
}


$url_project = '/index.php';


$template = render_template('layout.php', [
  'page_title'  => $config['site_name'],
  'projects'    => $projects,
  'project_url' => $url_project,
  'content'     => $content,
  'user_name'   => $user_name
]);


echo $template;
