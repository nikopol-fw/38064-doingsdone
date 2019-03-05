<?php

require_once 'utils/functions.php';
require_once 'utils/config.php';
require_once 'utils/db_config.php';
require_once 'mysql_helper.php ';


// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

//s$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

//$tasks = [
//  [
//    'title'     => 'Собеседование в IT компании',
//    'done_date' => '24.02.2019',
//    'category'  => 'Работа',
//    'is_done'   => false,
//  ], [
//    'title'     => 'Выполнить тестовое задание',
//    'done_date' => '25.12.2019',
//    'category'  => 'Работа',
//    'is_done'   => false,
//  ], [
//    'title'     => 'Сделать задание первого раздела',
//    'done_date' => '21.12.2019',
//    'category'  => 'Учеба',
//    'is_done'   => true,
//  ], [
//    'title'     => 'Встреча с другом',
//    'done_date' => '22.12.2019',
//    'category'  => 'Входящие',
//    'is_done'   => false,
//  ], [
//    'title'     => 'Купить корм для кота',
//    'done_date' => 'Нет',
//    'category'  => 'Домашние дела',
//    'is_done'   => false,
//  ], [
//    'title'     => 'Заказать пиццу',
//    'done_date' => 'Нет',
//    'category'  => 'Домашние дела',
//    'is_done'   => false,
//  ],
//];


if ($config['site_enable'] === false) {
  echo "Сайт на технических работах. Зайдите позже.";


} else {
  $db_conf = db_connect($db_host, $db_user, $db_pass, $db_name);


  // Получаем список задач $task
  $tasks = '';
  if (isset($_GET['id'])) {
    $sql = "SELECT task_name, date_done, project_id, flag_done"
          ."  FROM task"
          ." WHERE user_author = 1 AND project_id = ?;";
    $stmt = db_get_prepare_stmt($db_conf, $sql, [$_GET['id']]);

    // TODO возвращает true или false, нужно корректно обработать ошибку... Проверить работу при некорректном запросе
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

  } else {
    $sql = "SELECT task_name, date_done, project_id, flag_done"
          ."  FROM task"
          ." WHERE user_author = 1;";
    $result = mysqli_query($db_conf, $sql);
  }

  if (!$result) {

  } else {
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }
  // End Получаем список задач $task

  if (empty($tasks)) {
    http_response_code(404);
  }


  $content = render_template('index.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'tasks'               => $tasks,
  ]);


  // Получаем список проектов $projects и количество задач для каждого проекта
  $projects = '';
  $sql = "   SELECT p.project_id, project_name, COUNT(t.project_id) AS project_count\n"
        ."     FROM project AS p\n"
        ."LEFT JOIN task AS t\n"
        ."       ON p.project_id = t.project_id\n"
        ."    WHERE p.user_author = 1\n"
        ." GROUP BY project_name\n"
        ." ORDER BY p.project_id ASC;";

  $result = mysqli_query($db_conf, $sql);
  if (!$result) {

  } else {
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }
  // End Получаем список проектов $projects и количество задач для каждого проекта


  $scriptname = pathinfo(__FILE__, PATHINFO_BASENAME);
  $url_project = '/' . $scriptname;


  $template = render_template('layout.php', [
    'page_title'  => $config['site_name'],
    'projects'    => $projects,
    'tasks'       => $tasks,
    'project_url' => $url_project,
    'content'     => $content,
  ]);

  echo $template;
}
