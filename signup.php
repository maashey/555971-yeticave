<?php
require_once('error_reporting.php');
require_once('functions.php');
require_once('db_connect.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $required= ['email', 'password', 'name', 'message'];
    $errors = [];

    // Проверка обязательных полей
    foreach ($required as $field) {
        if (empty($_POST[$field]) || strlen(trim($_POST[$field])) == 0) {
            $errors[$field] = 'Это поле должно быть заполнено';
        }
    }
    // Проверка валидности email
    if (!isset($errors['email']) && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email';
    }

    //Валидация файла
    $file_message ='';
    if (isset($_FILES['avatar'])) {
        switch ($_FILES['avatar']['error']) {
            case UPLOAD_ERR_OK:
                $tmp_name = $_FILES['avatar']['tmp_name'];
                $file_name = $_FILES['avatar']['name'];
                $time = time();
                $avatar_path = 'img/'. $time. '_' . $file_name;
                $file_type = mime_content_type($tmp_name);

                if ($file_type != "image/png" && $file_type != "image/jpeg") {
                    $errors['avatar'] = 'Загрузите фото в формате JPG, JPEG или PNG';
                }
                else {
                    move_uploaded_file($tmp_name, $avatar_path);
                } 
                break;

            case UPLOAD_ERR_INI_SIZE:   
                $errors['avatar'] = 'Загрузка не удалась. Максимальный размер файла: 2МБ';
                break;

            case UPLOAD_ERR_NO_FILE:   
                $file_message = "Вы не загрузили файл, но это и не обязательно";
                break;

            default: 
                $errors['avatar'] = "Ошибка загрузки файла"; 
                break; 
        }
    }




    // Проверка, что email нет в таблице users
    if (!isset($errors['email'])) {

        $stmt = db_get_prepare_stmt($db, 'SELECT email FROM users WHERE email = ?', [$_POST['email']]);
        mysqli_stmt_execute($stmt);

        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            if (mysqli_num_rows($res) > 0) {
                $errors['email'] = 'Пользователь с таким email уже существует';
            }
        } else {
            $error_db = mysqli_error($db);
        }
    }

    //Валидация всей формы
    if (count($errors)) {
        $content = render_template('view_signup',  ['form' => $form, 'errors' => $errors, 'file_message' => $file_message ]);
    }
    else if (isset($error_db)) {
        $content = render_template('error', ['error' => $error_db]);
    }
    else {
        if(!isset($avatar_path)) {
            $avatar_path = '';
        }
        $query = 'INSERT INTO users (email, name, password, avatar_path, contacts) VALUES (?, ?, ?, ?, ?)';
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
        $stmt = db_get_prepare_stmt($db, $query, [ $form['email'], $form['name'], $password, $avatar_path, $form['message'] ]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            header("Location: /login.php");
            exit();
        }
        else {
            $error_db = mysqli_error($db);
            $content = render_template('error', ['error' => $error_db]);
        }
    }
} else {
    $content = render_template('view_signup', []);
}

render_page($content, 'Регистрация',  $categories);