<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 

date_default_timezone_set("Europe/Moscow");

require_once('functions.php');
require_once('data.php');

$tomorrow = strtotime('tomorrow') - time();
$hours = $tomorrow/3600;
$till_tomorrow['hours'] = floor($hours);
$till_tomorrow['minutes'] = floor(($hours - $till_tomorrow['hours'])*60);
$expiration = $till_tomorrow['hours'].':'.$till_tomorrow['minutes'];

$page_content = render_template('templates/index.php', ['lots' => $lots, 'expiration' => $expiration]);
$layout_content = render_template('templates/layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);

print($layout_content);
