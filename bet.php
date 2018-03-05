<?php
require_once('functions.php');
require_once('db_connect.php');

session_start();

//обработка формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$bet=[];
    parse_str( parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_QUERY ) );
    if (isset($id)){
        $bet['lot_id'] = $id;
    }
	else{
        header("Location: /index.php" );
        exit();
    }

	$sum = trim($_POST['cost']);

	//проверка авторизации и проверка значения поля ставки
	if ( isset($_SESSION['user']) && is_numeric($sum) && $sum > 0 ) {

		$bet['sum'] = intval($sum);
    	$bet['user_id'] = $_SESSION['user']['id'];

        $query = 'SELECT lots.author_id, lots.price, lots.expiration, lots.price_step, COALESCE(MAX(bets.sum), lots.price) as current_price
    			FROM lots 
    			LEFT OUTER JOIN bets 
    			ON lots.id = bets.lot_id 
   				WHERE lots.id ='. $bet['lot_id'] ;
        $result = mysqli_query($db, $query);

        if ($result){
        	$lot = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $min_bet = $lot['current_price'] + $lot['price_step'];

            //если  ставка пользователя больше или равно минимальной ставке
            if ($bet['sum'] >= $min_bet ) {
                $user_bet_exist = false;
                //выбираем ставки лота, если текущая цена больше начальной
                if ($lot['current_price'] > $lot['price']) {

                    $query = "SELECT bets.user_id 
                                FROM bets 
                                JOIN lots
                                ON bets.lot_id = lots.id
                                JOIN users
                                ON bets.user_id = users.id
                                WHERE bets.lot_id =" . $lot_id ;
                    $result2 = mysqli_query($db, $query);

                    if ($result2) {
                        $bets = mysqli_fetch_all($result2, MYSQLI_ASSOC);
                        $user_bet_exist = false;

                        //проверяем делал ли пользователь ставки
                        foreach ($bets as $bet){
                            if($bet['user_id']==$_SESSION['user']['id']){
                                $user_bet_exist = true;
                                break;
                            }
                        }
                    } else {
                        $content = render_template('error', ['error' => mysqli_error($db)]);
                    }
                }

                //если лот открыт, лот не создан пользователем и он еще не добавлял ставки, до добавляем ставку в таблицу
                if (strtotime($lot['expiration']) > time() && $lot['author_id'] != $bet['user_id'] && !$user_bet_exist) {
                    $query = 'INSERT INTO bets (sum, user_id, lot_id) VALUES ( ?, ?, ?)';
                    $stmt = db_get_prepare_stmt($db, $query, [$bet['sum'], $bet['user_id'], $bet['lot_id']]);
                    $res = mysqli_stmt_execute($stmt);
                    //показываем результат - страницу лота с добавленной ставкой или ошибку в случае ошибки
                    if ($res) {
                        header("Location: /lot.php?id=" . $bet['lot_id']);
                    } else {
                        $content = render_template('error', ['error' => mysqli_error($db)]);
                    }
                }
            }

            //если ставка меньше минимальной, то показываем страницу лота без изменений
            else{
                header("Location: /lot.php?id=". $bet['lot_id'] );
            }
        }
        else{
        	$content = render_template('error', ['error' => mysqli_error($db)]);
        	render_page($content , 'Добавление ставки', $categories);
        }
	}
	//если пользователь не авторизован или значение ставки не прошло валидацию, то показываем страницу лота без изменений
	else{
		header("Location: /lot.php?id=". $bet['lot_id'] ); 		
	}
}
else
{
	header("Location: /index.php" );
}