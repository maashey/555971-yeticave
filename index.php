<?php
require_once('error_reporting.php');

date_default_timezone_set("Europe/Moscow");

require_once('functions.php');
require_once('data.php');

session_start();

$content = render_template('view_index', ['lots' => $lots, 'expiration' => $expiration]);

render_page($content, 'Главная', $categories );
