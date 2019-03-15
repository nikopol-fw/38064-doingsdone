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
}

// Показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);


// Если пользователь не авторизован выводим страницу приветствия
if (!$is_auth) {
  $template = render_template('layout_welcome.php', [
    'page_title'  => $config['site_name'],
  ]);
  echo $template;
  exit(0);
}
// End


$db_conf = db_connect($db_host, $db_user, $db_pass, $db_name);


// Получаем список задач $task
$tasks = '';
if (isset($_GET['id'])) {
  $sql  = "SELECT task_name, date_done, project_id, flag_done\n"
        . "  FROM task\n"
        . " WHERE user_author = 1 AND project_id = ?;";
  $stmt = db_get_prepare_stmt($db_conf, $sql, [$_GET['id']]);

  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

} else {
  $sql    = "SELECT task_name, date_done, project_id, flag_done\n"
          . "  FROM task\n"
          . " WHERE user_author = 1;";
  $result = mysqli_query($db_conf, $sql);
}

if (!$result) {

} else {
  $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
// End


if (empty($tasks)) {
  http_response_code(404);
}


$content = render_template('index.php', [
  'show_complete_tasks' => $show_complete_tasks,
  'tasks'               => $tasks
]);


// Получаем список проектов $projects и количество задач для каждого проекта
$projects = '';
$sql = "   SELECT p.project_id, project_name, COUNT(t.project_id) AS project_count\n"
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
// End


$scriptname  = pathinfo(__FILE__, PATHINFO_BASENAME);
$url_project = '/' . $scriptname;


$template = render_template('layout.php', [
  'page_title'  => $config['site_name'],
  'projects'    => $projects,
  'tasks'       => $tasks,
  'project_url' => $url_project,
  'content'     => $content,
  'user_name'   => $user_name
]);

echo $template;
