<?php

//форматирование вывода цены 
function format_price($price) {
    $result=floatval($price); //переводим в число на всякий случай
    $result=ceil($result); //округляем до целого
    $result= number_format($result, 0, '', ' ');
    return $result;
}


//функция шаблонизатор
function render_template($template_name, $vars)
{
	$template_path = 'templates/'. $template_name .'.php' ;

	//Проверяем, что файл шаблона, переданный в аргументе, существует. Если не существует, то функция вернет пустую строку
  if (!is_file($template_path)){  
      $result='';    	
  }
  else{
    extract($vars); // extract делает из массива набор переменных в локальной области видимости
    ob_start();  //Включение буферизации вывода
    require_once($template_path); //переменные из extract будут видны внутри подключаемого файла
    $result = ob_get_clean();  //возвращаем буфер и очищаем
  } 
    return $result;
}


//фильтрация данных для показа на странице
function esc($str) {
	$text = htmlspecialchars($str);
	return $text;
}


//оборачивание контента в layout и вывод на экран
function render_page($content, $title, $categories, $is_index=NULL){
	$page = render_template('layout',
        [
            'content' => $content,
            'categories' => $categories,
            'title' => $title,
            'is_index' => $is_index
        ]
    );
	print($page);
}


//расчёт и форматирование оставшегося времени до конца лота
function format_expiration($expiration) {
    $diff = strtotime($expiration) - time();
    if ($diff>=604800){
        $res = date("d.m.y", strtotime($expiration)) ; 
    }
    else if ($diff<604800 && $diff>=86400){
        $res = round($diff/86400). ' д.' ;
    }
    else if ($diff<86400 && $diff>=3600){
        $res = round($diff/3600). ' ч.' ;
    }
    else if ($diff<3600){
        $res = round($diff/60). ' мин.' ;
    }
    return $res;
}


//форматирование времени ставки
function format_bet_time($seconds){
    $minutes = floor($seconds/60);
    $hours = floor($seconds/3600);
    $days = floor($seconds/86400);
    if ($minutes<60){
        $res = $minutes.' мин. назад';
    }
    else if ($hours<24 && $minutes>=60){
        $res = $hours.' ч. назад';
    }
    else if ($days<7 && $hours>=24){
        $res = $days.' дн. назад';
    }
    else{
        $res = date("d.m.y", (time() - $seconds) );
    }
    return $res;
}