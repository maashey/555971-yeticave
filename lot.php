<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 

date_default_timezone_set("Europe/Moscow");

require_once('functions.php');
require_once('data.php');

$lot = null;

if (isset($_GET['lot_id'])) {
	$lot_id = $_GET['lot_id'];

	foreach ($lots as $item) {
		if ($item['id'] == $lot_id) {
			$lot = $item;
			break;
		}
	}
}

if (!$lot) {
	http_response_code(404);
}

$content = render_template('view_lot', ['lot' => $lot, 'bets'=> $bets, 'expiration' => $expiration]);

render_page($content, esc($lot['name']), $categories, $is_auth, $user_name, $user_avatar);
