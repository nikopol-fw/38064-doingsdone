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


  $tasks = '';
  $sql = "SELECT task_name, date_done, project_id, flag_done"
        ."  FROM task"
        ." WHERE user_author = 1;";
  $result = mysqli_query($db_conf, $sql);
  if (!$result) {

  } else {
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }
  //var_dump($tasks);

  $content = render_template('index.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'tasks'               => $tasks,
  ]);


  $projects = '';
  $sql = "SELECT project_id, project_name"
        ."  FROM project"
        ." WHERE user_author = 1;";
  $result = mysqli_query($db_conf, $sql);
  if (!$result) {

  } else {
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }


  $template = render_template('layout.php', [
    'page_title' => $config['site_name'],
    'projects'   => $projects,
    'tasks'      => $tasks,
    'content'    => $content,
  ]);

  echo $template;
}
