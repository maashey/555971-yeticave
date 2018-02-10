<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 

require_once('functions.php');
require_once('data.php');

$page_content = render_template('templates/index.php', ['items' => $items]);
$layout_content = render_template('templates/layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);

print($layout_content);