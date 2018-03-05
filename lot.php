<?php
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
    $query = 'SELECT lots.id, lots.author_id, lots.name, lots.description, lots.img_path, lots.price, lots.expiration, lots.price_step,
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
        $lot['min_bet'] = $lot['current_price'] + $lot['price_step'];
    	$bet_message=''; //если ставка не может быть добавлен, то будет показано это сообщение вместо блока добавления ставки

        if (!isset($lot['name'])) {
            http_response_code(404);
            $error="Лот с этим id не найден";
            $content = render_template('error', ['error' => $error]);
        }
        else {
            if (!isset($_SESSION['user'])){
                $bet_message = 'Залогиньтесь, чтобы делать ставки';
            }
            else if (strtotime($lot['expiration']) < time() ){
                $bet_message = 'Срок действия лота истёк';
            }
            else if ($lot['author_id'] == $_SESSION['user']['id'] ){
                $bet_message = 'Вы не можете добавить ставку на свой лот';
            }

        	//выбираем ставки лота
        	if ($lot['current_price'] > $lot['price']){

	        	$query = "SELECT bets.sum as price, TIMESTAMPDIFF(SECOND, bets.dt_add,  NOW()) as bet_time , bets.user_id ,users.name as user_name
	        				FROM bets 
	        				JOIN lots
	        				ON bets.lot_id = lots.id
	        				JOIN users
	        				ON bets.user_id = users.id
	        				WHERE bets.lot_id =". $lot_id ." ORDER BY bet_time";

	        	$result2 = mysqli_query($db, $query);			

	        	if ($result2){
	        		$bets = mysqli_fetch_all($result2, MYSQLI_ASSOC);

	        		//проверяем делал ли пользователь ставки на этот лот
                    foreach ($bets as $bet){
                        if(isset($_SESSION['user']) && $bet['user_id']==$_SESSION['user']['id']){
                            $bet_message = 'Вы уже сделали ставку на этот лот';
                            break;
                        }
                    }
                    $content = render_template( 'view_lot', ['lot' => $lot, 'bets'=> $bets, 'bet_message' => $bet_message ] );
	        	}
	        	else{
	        		$content = render_template('error', ['error' => mysqli_error($db)]);
	        	}
	        }
	        else{
	            $content = render_template( 'view_lot', ['lot' => $lot,  'bet_message' => $bet_message ] );
        	}
        }
    }
    else {
        $content = render_template('error', ['error' => mysqli_error($db)]);
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
