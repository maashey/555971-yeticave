<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 

date_default_timezone_set("Europe/Moscow");

require_once('functions.php');
require_once('data.php');

$lot = null;

if (isset($_COOKIE['visited_lots'])) {
    $visited_lots = json_decode($_COOKIE['visited_lots']);
}
else {
    $visited_lots = [];
}

if (isset($_GET['lot_id'])) {
	$lot_id = $_GET['lot_id'];

    $lot = $lots[$lot_id] ?? null;
    if(!in_array($lot_id, $visited_lots)) {
        array_push($visited_lots, $lot_id);
        setcookie('visited_lots', json_encode($visited_lots), strtotime("+30 days"));
    }
}

if (!$lot) {
	http_response_code(404);
}

$content = render_template('view_lot', ['lot' => $lot, 'bets' => $bets, 'expiration' => $expiration]);

render_page($content, esc($lot['name']), $categories, $is_auth, $user_name, $user_avatar);
