<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 

require_once('functions.php');

$is_auth = (bool) rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

//список категорий
$categories= ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

//список объявлений
$items=[];
$items[]= item_to_arr('2014 Rossignol District Snowboard','Доски и лыжи', 10999,'img/lot-1.jpg');
$items[]= item_to_arr('DC Ply Mens 2016/2017 Snowboard','Доски и лыжи', 159999,'img/lot-2.jpg');
$items[]= item_to_arr('Крепления Union Contact Pro 2015 года размер L/XL','Крепления', 8000,'img/lot-3.jpg');
$items[]= item_to_arr('Ботинки для сноуборда DC Mutiny Charocal','Ботинки', 10999,'img/lot-4.jpg');
$items[]= item_to_arr('Куртка для сноуборда DC Mutiny Charocal','Одежда', 7500,'img/lot-5.jpg');
$items[]= item_to_arr('Маска Oakley Canopy','Разное',5400 ,'img/lot-6.jpg');

?>