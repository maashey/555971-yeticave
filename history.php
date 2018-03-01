<?php
require_once('error_reporting.php');
require_once('functions.php');
require_once('data.php');

session_start();

if (isset($_COOKIE['visited_lots'])) {
    $visited_lots = json_decode($_COOKIE['visited_lots']);
}
else {
    $visited_lots = [];
}

$content =  render_template( 'view_history', ['lots' => $lots, 'expiration' => $expiration, 'visited_lots' => $visited_lots]);

render_page($content, 'История просмотров', $categories );