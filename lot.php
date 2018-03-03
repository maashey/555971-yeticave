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
    $query = 'SELECT lots.name, lots.description, lots.img_path, lots.price, lots.expiration, lots.price_step,
    			COALESCE( MAX(bets.sum), lots.price) as current_price, cat.name as category
    			FROM lots 
    			LEFT OUTER JOIN bets 
    				ON lots.id = bets.lot_id 
    			JOIN categories cat 
    				ON lots.category_id = cat.id 
    				WHERE lots.id ='. $lot_id;

    $result = mysqli_query($db, $query);    

    if ($result) {
    	$lot = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if (!isset($lot['name'])) {
            http_response_code(404);
            $error="Лот с этим id не найден";
            show_error($content, $error);
        }
        else {
        	//выбираем ставки лота
        	if ($lot['current_price'] > $lot['price']){

	        	$query = "SELECT bets.sum as price, TIMESTAMPDIFF(MINUTE, bets.dt_add,  NOW()) as bet_time , users.name as user_name
	        				FROM bets 
	        				JOIN lots
	        				ON bets.lot_id = lots.id
	        				JOIN users
	        				ON bets.user_id = users.id
	        				WHERE bets.lot_id =". $lot_id ; //. "ORDER BY bet_time";

	        	$result2 = mysqli_query($db, $query);			

	        	if ($result2){
	        		$bets = mysqli_fetch_all($result2, MYSQLI_ASSOC);

		            $lot['min_bet'] = $lot['current_price'] + $lot['price_step'];
		            $content = render_template( 'view_lot', ['lot' => $lot, 'bets'=> $bets ] );	     
	        	}
	        	else{
	        		$content = render_template('error', ['error' => mysqli_error($db)]);
	        	}
	        }
	        else{
	            $lot['min_bet'] = $lot['current_price'] + $lot['price_step'];
	            $content = render_template( 'view_lot', ['lot' => $lot ] );
        	}
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
