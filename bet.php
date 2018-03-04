<?
require_once('error_reporting.php');
require_once('functions.php');
require_once('db_connect.php');

session_start();

//обработка формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$bet=[];
	$bet['lot_id'] = intval($_POST['lot_id']);	
	$min_bet = intval($_POST['min_bet']);

	//валидация и проверка ставки, если пользователь авторизован
	if ( isset($_SESSION['user']) && !empty($_POST['cost']) && intval(trim($_POST['cost'])) >= $min_bet ) {

		$bet['sum'] = intval(trim($_POST['cost']));
    	$bet['user_id'] = $_SESSION['user']['id'];

        $query = "SELECT * FROM lots WHERE lots.id=". $bet['lot_id'] ;    
        $result = mysqli_query($db, $query);
        if ($result){
        	$lot = mysqli_fetch_array($result, MYSQLI_ASSOC);

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
                    $user_bet_exist = false;

                    //проверяем делал ли пользователь ставки
                    foreach ($bets as $bet){
                        if(isset($_SESSION['user']) && $bet['user_id']==$_SESSION['user']['id']){
                            $user_bet_exist = true;
                            break;
                        }
                    }
                 }
                else{
                    $content = render_template('error', ['error' => mysqli_error($db)]);
                }
            }

            //если лот открыт, лот не создан пользователем и он еще не добавлял ставки, до добавляем ставку в таблицу
            if (  strtotime($lot['expiration']) > time()  &&  $lot['author_id'] != $bet['user_id']  &&  !$user_bet_exist ) {
                $query = 'INSERT INTO bets (sum, user_id, lot_id) VALUES ( ?, ?, ?)';
                $stmt = db_get_prepare_stmt($db, $query, [$bet['sum'], $bet['user_id'], $bet['lot_id']]);
                $res = mysqli_stmt_execute($stmt);
                //показываем результат - страницу лота с добавленной ставкой или ошибку в случае ошибки
                if ($res) {
                    header("Location: /lot.php?id=". $bet['lot_id'] ); 
                }
                else {
                    $content = render_template('error', ['error' => mysqli_error($db)]);
                }
            }

        }
        else{
        	$content = render_template('error', ['error' => mysqli_error($db)]);
        	render_page($content , 'Добавление ставки', $categories);
        }
	}
	//если пользователь не авторизован или значение ставки не прошло валидацию, то показывает страницу лота без изменений
	else{
		header("Location: /lot.php?id=". $bet['lot_id'] ); 		
	}
}
else
{
	header("Location: /index.php" );
}