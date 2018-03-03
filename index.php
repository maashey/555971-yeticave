<?php
require_once('error_reporting.php');
require_once('functions.php');
require_once('db_connect.php');

date_default_timezone_set("Europe/Moscow");

session_start();

//Получаем 9 самых новых лотов 
$query='
	SELECT lots.id, lots.name, lots.price, lots.img_path as img, lots.expiration , cat.name as category
	FROM lots JOIN categories cat
	    ON lots.category_id = cat.id
	WHERE lots.expiration > NOW()
	ORDER BY lots.dt_add DESC
	LIMIT 9';

$result = mysqli_query($db, $query);

if ($result) {
	$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysqli_error($db);
    $content = render_template('error', ['error' => $error]);
}


$content = render_template('view_index', ['lots' => $lots]);

render_page($content, 'Главная', $categories , $is_index = true );
