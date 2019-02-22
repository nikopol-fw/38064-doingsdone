<?php

require_once 'utils/functions.php';

$site_title = 'Дела в порядке';

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// Дела в порядке

$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

$tasks = [
    [
        'title' => 'Собеседование в IT компании',
        'done_date' => '01.12.2019',
        'category' => 'Работа',
        'is_done' => false
    ], [
        'title' => 'Выполнить тестовое задание',
        'done_date' => '25.12.2019',
        'category' => 'Работа',
        'is_done' => false
    ], [
        'title' => 'Сделать задание первого раздела',
        'done_date' => '21.12.2019',
        'category' => 'Учеба',
        'is_done' => true
    ], [
        'title' => 'Встреча с другом',
        'done_date' => '22.12.2019',
        'category' => 'Входящие',
        'is_done' => false
    ], [
        'title' => 'Купить корм для кота',
        'done_date' => 'Нет',
        'category' => 'Домашние дела',
        'is_done' => false
    ], [
        'title' => 'Заказать пиццу',
        'done_date' => 'Нет',
        'category' => 'Домашние дела',
        'is_done' => false
    ]
];


$content = render_template('index.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'tasks' => $tasks
]);

$template = render_template('layout.php', [
    'site_title' => $site_title,
    'projects' => $projects,
    'tasks' => $tasks,
    'content' => $content
]);

echo $template;
