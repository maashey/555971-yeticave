<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 

require_once('functions.php');
require_once('data.php');

session_start();

if (!isset($_SESSION['user'])) {
    http_response_code(403);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$lot = $_POST;

	$required = ['name', 'description', 'price', 'step', 'category', 'expiration'];
	$dict = ['name' => 'Наименование', 'description' => 'Описание', 'img' => 'Фото лота', 'price' => 'Начальная цена', 'step' => 'Шаг ставки',  'category' => 'Категория', 'expiration'=> 'Дата окончания торгов'];
	$errors = [];
	foreach ($required as $field) {
		if (empty($lot[$field])) {
            $errors[$field] = 'Поле не заполнено';
		}
	}

	foreach ($lot as $field => $value) {
		if ($field == "price") {
			if (!is_numeric($value) && !isset($errors[$field])) {
				$errors[$field] = 'Введите число';
			}
		}
		if ($field == "step") {
			if (!is_numeric($value) && !isset($errors[$field])) {
				$errors[$field] = 'Введите число';
			}
		}
		if($field == "category"){
			if ($value == 'Выберите категорию') {
				$errors[$field] = 'Вы не выбрали категорию';
			}
		}
		if ($field == "expiration"){
		    if (strtotime($value) < time() && !isset($errors[$field])) {
                $errors[$field] = 'Укажите дату из будущего';
            }
        }
	}

	if (isset($_FILES['img']['name']) && $_FILES['img']['size'] >0 ) {
		$tmp_name = $_FILES['img']['tmp_name'];
		$file_name = $_FILES['img']['name'];
		$file_path = 'img/'. $file_name;
		$file_size = $_FILES['img']['size'];
		$file_type = mime_content_type($tmp_name);

		if ($file_type !== "image/jpeg") {
			$errors['img'] = 'Загрузите фото в формате jpg или jpeg';
		}
		else if ($file_size > 2097152) {
			$errors['img'] = 'Максимальный размер файла: 2МБ';
		}
		else {
			move_uploaded_file($tmp_name, $file_path);
			$lot['img'] = $file_path;
		}		
	}
	else {
		$errors['img'] = 'Вы не загрузили файл';
	}	

	if (count($errors)) {
		$content = render_template('view_add', ['lot' => $lot, 'errors' => $errors, 'dict' => $dict, 'categories' => $categories]);
	}
	else {	
		$content = render_template('view_lot', ['lot' => $lot, 'expiration' => $lot['expiration']]);
	}	
} 
else 
{
	$content = render_template('view_add', ['categories' => $categories ]);
}

render_page($content, 'Добавление лота', $categories, $user_avatar);
