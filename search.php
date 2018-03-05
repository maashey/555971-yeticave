<?php
require_once('functions.php');
require_once('db_connect.php');

session_start();


$search = $_GET['q'] ?? '';
$search = trim($search);
if ($search != '') {
    $title = 'Результаты поиска по запросу '.esc($search);
    $search = mysqli_real_escape_string($db, $search );
    // запрос на поиск лотов по имени или описанию
    $query = "
            SELECT lots.id, lots.name, lots.price, lots.img_path as img, lots.expiration, cat.name as category
            FROM lots JOIN categories cat
            ON lots.category_id = cat.id
            WHERE lots.name LIKE '%$search%' OR lots.description LIKE '%$search%' 
            ORDER BY lots.dt_add DESC";

    if ($lots = mysqli_query($db, $query)) {
        // передаем в шаблон результат выполнения
        $content = render_template('view_search', ['lots' => $lots, 'title' => $title]);
    }
    else {
        //показываем ошибку
        $content = render_template('error', ['error' => mysqli_error($db)]);
    }
}
else{
    header("Location:". $_SERVER['HTTP_REFERER']  );
    exit();
}

render_page( $content, $title, $categories);