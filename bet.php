<?
require_once('error_reporting.php');
require_once('functions.php');
require_once('db_connect.php');

session_start();

// Проверяем, что пользователь залогинен
if(!isset($_SESSION['user'])) {
    $page_content = render_template('error', ['error' => 'Залогиньтесь чтобы сделать ставку']);
}

//обработка формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$bet=[];
	$bet['lot_id'] = intval($_POST['lot_id']);	
	$min_bet = intval($_POST['min_bet']);

	//валидация и проверка ставки 
	if (!empty($_POST['cost']) && intval(trim($_POST['cost'])) >= $min_bet ) {

		$bet['sum'] = intval(trim($_POST['cost']));
    	$bet['user_id'] = $_SESSION['user']['id'];

        $query = "SELECT * FROM lots WHERE lots.id=". $bet['lot_id'] ;    
        $result = mysqli_query($db, $query);
        if ($result){
        	$lot = mysqli_fetch_array($result, MYSQLI_ASSOC);

        	if (strtotime($lot['expiration']) < time()) {
                $content = render_template('error',  ['error' => 'Срок действия лота истек']);
                render_page($content , 'Добавление ставки', $categories);
            }
        	else if ($lot['author_id'] == $bet['user_id']) {
                $content = render_template('error',  ['error' => 'Вы не можете добавить ставку на свой лот']);
                render_page($content , 'Добавление ставки', $categories);
            }            
            else {
                $query = 'INSERT INTO bets (sum, user_id, lot_id) VALUES ( ?, ?, ?)';
                $stmt = db_get_prepare_stmt($db, $query, [$bet['sum'], $bet['user_id'], $bet['lot_id']]);
                $res = mysqli_stmt_execute($stmt);
                if ($res) {
                    header("Location: /lot.php?id=". $bet['lot_id'] ); 
                }
                else {
                    $page_content = render_template('error', ['error' => mysqli_error($link)]);
                }
            }

        }
        else{
        	$content = render_template('error', ['error' => mysqli_error($db)]);
        	render_page($content , 'Добавление ставки', $categories);
        }
	}
	else{
		header("Location: /lot.php?id=". $bet['lot_id'] ); 		
	}
}
else
{
	header("Location: /index.php" );
}