<?php
require_once('functions.php');
require_once('mysql_helper.php');
require_once 'dbconfig.php';

//Подключаетмся к бд
$db = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
mysqli_set_charset($db, "utf8");

$categories = [];

//Если соединение успешно, то получаем список категорий
if (!$db) {
    $error = mysqli_connect_error();
    $content = render_template('error', ['error' => $error]);
    render_page($content , 'Что-то пошло не так...', $categories);
}
else {
    $query = 'SELECT * FROM categories ORDER BY `id`';
    $result = mysqli_query($db, $query);

    if ($result) {
        $result_arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($result_arr as $r) {
            $categories[$r['id']] = $r['name'];
        }
    }
    else {
        $content = render_template('error', ['error' => mysqli_error($db)]);
        render_page($content , 'Что-то пошло не так...', $categories);
    }
}
