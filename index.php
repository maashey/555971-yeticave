<?php
require_once('error_reporting.php');
require_once('functions.php');
require_once('db_connect.php');

date_default_timezone_set("Europe/Moscow");

session_start();

//показ лотов внутри категории
if (isset($_GET['category_id'])){
    $is_index = NULL;

    $category_id = $_GET['category_id'];
    //Получаем 9 самых новых открытых лотов выбранной категории
    $query = '
        SELECT lots.id, lots.name, lots.price, lots.img_path as img, lots.expiration , cat.name as category
        FROM lots JOIN categories cat
        ON lots.category_id = cat.id
        WHERE lots.expiration > NOW() AND lots.category_id='. $category_id
        .' ORDER BY lots.dt_add DESC
        LIMIT 9';

    $result = mysqli_query($db, $query);

    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($db);
        $content = render_template('error', ['error' => $error]);
    }
    $content = render_template('view_index', ['lots' => $lots]);

}
else {
    $is_index = 1;
    //Получаем 9 самых новых открытых лотов
    $query = '
        SELECT lots.id, lots.name, lots.price, lots.img_path as img, lots.expiration , cat.name as category
        FROM lots JOIN categories cat
            ON lots.category_id = cat.id
        WHERE lots.expiration > NOW()
        ORDER BY lots.dt_add DESC
        LIMIT 9';

    $result = mysqli_query($db, $query);

    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($db);
        $content = render_template('error', ['error' => $error]);
    }
    $content = render_template('view_index', ['lots' => $lots]);
}



render_page($content, 'Главная', $categories , $is_index);
