<?php
require_once('error_reporting.php');
require_once('functions.php');
require_once('db_connect.php');

session_start();

if (isset($_COOKIE['visited_lots'])) {
    $visited_lots = json_decode($_COOKIE['visited_lots']);
}
else {
    $visited_lots = [];
}
$query = 'SELECT lots.id, lots.name, lots.img_path, lots.price, lots.expiration, cat.name as category
    FROM lots 
    JOIN categories cat ON lots.category_id = cat.id 
    WHERE lots.id IN (' . implode(",", $visited_lots) . ')';
if ($res = mysqli_query($db, $query)) {
    $lots_arr = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $lots=[];
    foreach ($visited_lots as $index){
        foreach ($lots_arr as $lot){
            if($lot['id'] == $index){
                $lots[]=$lot;
            }
        }
    }

    $content = render_template('view_history', ['lots' => array_reverse($lots)] );
}
else {
    $content = render_template('error', ['error' => mysqli_error($db)]);
}

render_page($content, 'Просмотренные лоты', $categories );
