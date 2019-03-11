<?php

require_once 'utils/functions.php';
require_once 'utils/config.php';
require_once 'utils/db_config.php';
require_once 'mysql_helper.php';


if ($config['site_enable'] === false) {
  echo 'Сайт на технических работах. Зайдите позже.';


} else {
  $db_conf = db_connect($db_host, $db_user, $db_pass, $db_name);


  // Получаем список проектов $projects и количество задач для каждого проекта
  $projects = '';
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
    $task = $_POST['task'];
    $errors_post = [];


    $required = ['name', 'project', 'date'];
    define('ERRORS_TASK', [
      'name'         => 'Укажите название для задачи',
      'project'      => 'Выберите проект из списка существующих проектов',
      'date'         => 'Выберите дату',
      'date_format'  => 'Дата должна быть указана в формате "ДД.ММ.ГГГГ" либо "ГГГГ-ММ-ДД"',
      'date_expired' => 'Дата должна быть больше или равна текущей',
    ]);

    foreach ($required as $field) {
      // Проверка обязательных полей
      if (empty($task[$field])) {
        $errors_post[$field] = ERRORS_TASK[$field];

        // Проверка полей на кастомные условия
      } else {
        switch ($field) {
          case 'project':
            if (!in_array($task[$field], array_column($projects, 'project_id'), true)) {
              $errors_post[$field] = ERRORS_TASK[$field];
            }
            break;

          case 'date':
            if (!check_date_format($task[$field])) {
              $errors_post[$field] = ERRORS_TASK['date_format'];
              break;
            }
            if (strtotime($task[$field]) < time()) {
              $errors_post[$field] = ERRORS_TASK['date_expired'];
              break;
            }
            break;

          default:
            break;
        }
      }
    }


    if (count($errors_post)) {
      $content = render_template('form_task.php', [
        'projects' => $projects,
        'errors' => $errors_post,
        'task' => $task
      ]);

      // Нет ошибок при отправке формы
    } else {
      $sql = "INSERT INTO task (date_create, date_done, flag_done, task_name, user_author, project_id)\n"
       . "         VALUES (NOW(), ?, FALSE, ?, 1, ?);";
      $stmt = db_get_prepare_stmt($db_conf, $sql, [$task['date'], $task['name'], $task['date']]);
      mysqli_stmt_execute($stmt);

      header('Location: index.php');
      exit(0);
    }

    // Сценарий вызван без метода POST
  } else {
    $content = render_template('form_task.php', [
      'projects' => $projects,
    ]);
  }





  $url_project = '/index.php';


  $template = render_template('layout.php', [
    'page_title'  => $config['site_name'],
    'projects'    => $projects,
    'project_url' => $url_project,
    'content'     => $content,
  ]);


  echo $template;
}
