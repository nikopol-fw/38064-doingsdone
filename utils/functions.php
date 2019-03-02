<?php
function db_connect ($host, $user, $pass, $db_name) {
  $con = mysqli_connect($host, $user, $pass, $db_name);
  mysqli_set_charset($con, 'utf-8');

  if (!$con) {
    $error = 'Ошибка подключения к БД: ' . mysqli_connect_error();
    $content = '<p>' . $error . '</p>';
    $page = render_template('layout.php', [
      'page_title' => 'Дела в порядке — ошибка подключения к БД',
      'projects' => null,
      'tasks' => null,
      'content' => $content
    ]);

    echo $page;
    exit(1);
  }

  return $con;
}
/**
 * Функция выводит шаблон
 *
 * @param string $name Название/путь шаблона от папки /templates/
 * @param array  $data Ассоциативный массив с данными для шаблона
 *
 * @return string итоговый HTML-код с подставленными данными
 */
function render_template ($name, $data = null) {
  $path   = __DIR__ . '/../templates/' . $name;
  $result = '';

  if (is_readable($path)) {
    ob_start();
    extract($data);

    require $path;
    $result = ob_get_clean();
  }

  return $result;
}

/**
 * Получить количество категорий
 *
 * @param $tasks
 * @param $project_id
 *
 * @return int
 */
function get_tasks_count ($tasks, $project_id) {
  $count = 0;
  foreach ($tasks as $value) {
    if ($value['project_id'] === $project_id) {
      $count++;
    }
  }
  return $count;
}

/**
 * Проверяет время до окончания задачи
 * Больше 24 часов = false
 * Меньше или равно = true
 *
 * @param string $task_date Дата окончания задачи
 *
 * @return bool
 */
function checkTask24h ($task_date) {
  $DAY_TIME = 86400;
  $isEnding = false;

  $time_stamp = strtotime($task_date) ?: 0;
  $time_diff  = $time_stamp - time();
  if ($time_diff > 0 && $time_diff <= $DAY_TIME) {
    $isEnding = true;
  }

  return $isEnding;
}
