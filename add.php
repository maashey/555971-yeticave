<?php
require_once('error_reporting.php');
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

    // Проверка обязательных полей
	foreach ($required as $field) {
		if (empty($_POST[$field]) || strlen(trim($_POST[$field])) ==0  ) {
            $errors[$field] = 'Поле не заполнено';
		}
	}

	//Валидация числовых полей, названия категории и даты
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

    //Валидация файла
    if ( isset($_FILES['img'])) { 
    	switch ($_FILES['img']['error']) {
    		case UPLOAD_ERR_OK:
		        $tmp_name = $_FILES['img']['tmp_name'];
		        $file_name = $_FILES['img']['name'];
		        $time = time();
		        $file_path = 'img/'. $time. '_' . $file_name;
		        $file_type = mime_content_type($tmp_name);

		        if ($file_type != "image/png" && $file_type != "image/jpeg") {
		            $errors['img'] = 'Загрузите фото в формате JPG, JPEG или PNG';
		        }
		        else {
		            move_uploaded_file($tmp_name, $file_path);
		            $lot['img'] = $file_path;
		        } 
    			break;

            case UPLOAD_ERR_INI_SIZE:   
                $errors['img'] = 'Загрузка не удалась. Максимальный размер файла: 2МБ';
                break;

            case UPLOAD_ERR_NO_FILE:   
                $errors['img'] = "Вы не загрузили файл";
                break;    			
    		
    		default:
    			$errors['img'] = "Ошибка загрузки файла"; 
    			break;
    	}
    }


    //Валидация всей формы
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

render_page($content, 'Добавление лота', $categories);
