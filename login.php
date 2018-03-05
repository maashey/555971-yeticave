<?php
require_once('functions.php');
require_once('db_connect.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;

    // Проверка обязательных полей
    $required = ['email', 'password'];
    $errors = [];
    foreach ($required as $field) {
        if (empty($_POST[$field]) || strlen(trim($_POST[$field])) == 0  ) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }
    // Проверка валидности email
    if (!isset($errors['email']) && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email';
    }

    //Аутентификация
    if (!count($errors)) {
        $stmt = db_get_prepare_stmt($db, 'SELECT * FROM users WHERE email = ?', [strtolower($_POST['email']) ]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            if (mysqli_num_rows($res) > 0) {
                $user = mysqli_fetch_assoc($res);
                if (password_verify($form['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                } else {
                    $errors['password'] = 'Вы ввели неверный пароль';
                }
            } else {
                $errors['email'] = 'Такой пользователь не найден';
            }
        } else {
            $error_db = 'Ошибка БД: ' . mysqli_error($db);
        }
    }


    if (isset($error_db)) {
        $content = render_template('error', ['error' => $error_db]);
    }
    else if (count($errors)) {
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

render_page($content, 'Вход', $categories );