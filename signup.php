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
    if (isset($_FILES['avatar']['name']) && $_FILES['avatar']['size'] >0) {
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $file_name = $_FILES['avatar']['name'];
        $avatar_path = 'img/'. $file_name;
        $file_size = $_FILES['avatar']['size'];
        $file_type = mime_content_type($tmp_name);

        if ($file_type !== "image/png" && $file_type !== "image/jpeg") {
            $errors['avatar'] = 'Загрузите фото в формате jpg, jpeg или png';
        }
        else if ($file_size > 2097152) {
            $errors['avatar'] = 'Максимальный размер файла: 2МБ';
        }
        else {
            move_uploaded_file($tmp_name, $avatar_path);
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
            $errors['db'] = mysqli_error($db);
        }
    }

    //Валидация всей формы
    if (count($errors)) {
        $content = render_template('view_signup',  ['form' => $form, 'errors' => $errors]);
    }
    else if (isset($errors['db'])) {
        $content = render_template('error', ['error' => $errors['db']]);
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
            $errors['db'] = mysqli_error($db);
            $content = render_template('error', ['error' => $errors['db']]);
        }
    }
} else {
    $content = render_template('view_signup', []);
}

render_page($content, 'Регистрация',  $categories);