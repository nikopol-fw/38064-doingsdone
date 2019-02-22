<?php
/**
 * Функция выводит шаблон
 * @param string $name Название/путь шаблона от папки /templates/
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string итоговый HTML-код с подставленными данными
 */
function render_template ($name, $data = null) {
    $path = 'templates/' . $name;
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
 * @param $tasks
 * @param $category
 * @return int
 */
function get_tasks_count ($tasks, $category) {
    $count = 0;
    foreach ($tasks as $value) {
        if ($value['category'] === $category) {
            $count++;
        }
    }
    return $count;
}
