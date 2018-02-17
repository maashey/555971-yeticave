<?php

//записываем в ассоциативный массив данные объявления
function item_to_arr($name, $cat, $price, $img) {
    return array('name'=> $name, 'category'=> $cat, 'price'=> $price, 'img'=> $img);
}


//форматирование вывода цены 
function format_price($price) {
    $result=floatval($price); //переводим в число на всякий случай
    $result=ceil($result); //округляем до целого
    $result= number_format($result, 0, '', ' ');
    //$result.= ' &#8381;'; //знак рубля у меня в браузере не работает. Знак подставляется картинкой в вёрстке lot__cost
    return $result;
}


//функция шаблонизатор
function render_template($template_path, $vars)
{
		//Проверяем, что файл шаблона, переданный в аргументе, существует. Если не существует, то функция вернет пустую строку
    if (!is_file($template_path)){  
        $result='';    	
    }
    extract($vars); // extract делает из массива набор переменных в локальной области видимости
    ob_start();  //Включение буферизации вывода
    require_once($template_path); //переменные из extract будут видны внутри подключаемого файла
    $result = ob_get_clean();  //возвращаем буфер и очищаем
    return $result;
}

//фильтрация данных 
function esc($str) {
	$text = htmlspecialchars($str);
	return $text;
}

//оборачивание контента в layout и вывод на экран
function render_page($content, $title, $categories, $is_auth, $user_name, $user_avatar){
	$page = render_template('templates/layout.php', [
    'content' => $content,
    'categories' => $categories,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
	]);
	print($page);
}