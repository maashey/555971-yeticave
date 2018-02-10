<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 

$is_auth = (bool) rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

//список категорий
$categories= ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

//записываем в ассоциативный массив данные объявления
function item_to_arr($name, $cat, $price, $img) {
    return array('name'=> $name, 'category'=> $cat, 'price'=> $price, 'img'=> $img);
}

//список объявлений
$items=[];
$items[]= item_to_arr('2014 Rossignol District Snowboard','Доски и лыжи', 10999,'img/lot-1.jpg');
$items[]= item_to_arr('DC Ply Mens 2016/2017 Snowboard','Доски и лыжи', 159999,'img/lot-2.jpg');
$items[]= item_to_arr('Крепления Union Contact Pro 2015 года размер L/XL','Крепления', 8000,'img/lot-3.jpg');
$items[]= item_to_arr('Ботинки для сноуборда DC Mutiny Charocal','Ботинки', 10999,'img/lot-4.jpg');
$items[]= item_to_arr('Куртка для сноуборда DC Mutiny Charocal','Одежда', 7500,'img/lot-5.jpg');
$items[]= item_to_arr('Маска Oakley Canopy','Разное',5400 ,'img/lot-6.jpg');

//форматирование вывода цены 
function format_price($price) {
    $result=floatval($price); //переводим в число на всякий случай
    $result=ceil($result); //округляем до целого
    $result= number_format($result, 0, '', ' ');
    //$result.= ' &#8381;'; //знак рубля у меня в браузере не работает. Знак подставляется картинкой в вёрстке lot__cost
    return $result;
}

?>