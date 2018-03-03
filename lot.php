<?php
require_once('error_reporting.php');
require_once('functions.php');
require_once('db_connect.php');

session_start();

$lot = null;

if (isset($_COOKIE['visited_lots'])) {
    $visited_lots = json_decode($_COOKIE['visited_lots']);
}
else {
    $visited_lots = [];
}

if (isset($_GET['id'])) {
	$lot_id = intval($_GET['id']);

    // запрос на показ лота по ID
    $query = "SELECT lots.name, lots.description, lots.img_path, lots.price, lots.expiration, lots.price_step, 
    			COALESCE( MAX(bets.sum), lots.price) as current_price, cat.name as category
    			FROM lots 
    			LEFT OUTER JOIN bets 
    				ON lots.id = bets.lot_id 
    			JOIN categories cat 
    				ON lots.category_id = cat.id 
    				WHERE lots.id =". $lot_id;

    $result = mysqli_query($db, $query);    

    if ($result) {
    	$lot = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if (!isset($lot['name'])) {
            http_response_code(404);
            $error="Лот с этим id не найден";
            show_error($content, $error);
        }
        else {
            // передаем в шаблон результат выполнения
            $content = render_template('view_lot', ['lot' => $lot] );
        }
    }
    else {
        show_error($content, mysqli_error($db));
    }
}


if(isset($lot) && !in_array($lot_id, $visited_lots)) {
    array_push($visited_lots, $lot_id);
    setcookie('visited_lots', json_encode($visited_lots), strtotime("+30 days"));
}

if (!$lot) {
	http_response_code(404);
}

render_page($content, esc($lot['name']), $categories );
