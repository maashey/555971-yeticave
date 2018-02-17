<?php
// ставки пользователей, которыми надо заполнить таблицу
$bets = [
    ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) .' minute')],
    ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) .' hour')],
    ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime('-' . rand(25, 50) .' hour')],
    ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')]
];

$is_auth = (bool) rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

//список категорий
$categories= ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

//список объявлений
$lots=[];
$lots[]= item_to_arr('2014 Rossignol District Snowboard','Доски и лыжи', 10999,'img/lot-1.jpg');
$lots[]= item_to_arr('DC Ply Mens 2016/2017 Snowboard','Доски и лыжи', 159999,'img/lot-2.jpg');
$lots[]= item_to_arr('Крепления Union Contact Pro 2015 года размер L/XL','Крепления', 8000,'img/lot-3.jpg');
$lots[]= item_to_arr('Ботинки для сноуборда DC Mutiny Charocal','Ботинки', 10999,'img/lot-4.jpg');
$lots[]= item_to_arr('Куртка для сноуборда DC Mutiny Charocal','Одежда', 7500,'img/lot-5.jpg');
$lots[]= item_to_arr('Маска Oakley Canopy','Разное',5400 ,'img/lot-6.jpg');


$tomorrow = strtotime('tomorrow') - time();
$hours = $tomorrow/3600;
$till_tomorrow['hours'] = floor($hours);
$till_tomorrow['minutes'] = floor(($hours - $till_tomorrow['hours'])*60);
$expiration = $till_tomorrow['hours'].':'.$till_tomorrow['minutes'];
