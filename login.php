<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('functions.php');
require_once('data.php');
require_once('userdata.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;

    $required = ['email', 'password'];
    $errors = [];
    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }
    if (!isset($errors['email']) && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email должен быть корректным';
    }

//Аутентификация
    $user = searchUserByEmail($form['email'], $users);
    if (isset($user) && !count($errors)) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        }
        else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }
    }
    elseif (!isset($user)) {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $content = render_template('view_login', ['form' => $form, 'errors' => $errors]);
    }
    else {
        header("Location: /index.php");
        exit();
    }
}
else {
    if (isset($_SESSION['user'])) {
        $content = render_template('welcome', ['username' => $_SESSION['user']['name']]);
    }
    else {
        $content = render_template('view_login', []);
    }
}

render_page($content, 'Вход', $categories, $user_avatar);