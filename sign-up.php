<?php
require_once('helpers.php');
require_once('functions.php');
require_once('db_connection.php');
require_once('init.php');

$sql_categories = mysqli_query($db_connection, "SELECT name FROM category");
$categories = [];
while ($category = mysqli_fetch_array($sql_categories, MYSQLI_ASSOC)) {
    array_push($categories, $category['name']);
}

$errors = [];
$warning_about_errors = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['email', 'password', 'name', 'message'];
    $rules = [
        'email' => function () use ($db_connection) {
            return validateEmail($_POST['email'], $db_connection);
        },
        'password' => function () {
            return validatePassword($_POST['password']);
        },
        'name' => function () {
            return validateName($_POST['name'], 25);
        },
        'message' => function () {
            return validateContacts($_POST['message'], 100);
        }
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }
    $errors = array_filter($errors);

    if (!empty($errors)) {
        $warning_about_errors = "Пожалуйста, исправьте ошибки в форме.";
    }
    if (empty($errors)) {
        $email = mysqli_real_escape_string($db_connection, $_POST['email']);
        $password = mysqli_real_escape_string($db_connection, $_POST['password']);
        $name = mysqli_real_escape_string($db_connection, $_POST['name']);
        $contacts = mysqli_real_escape_string($db_connection, $_POST['message']);
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql_insert_user = "INSERT INTO user (name, email, password, contacts) values ('$name', '$email', '$password_hash', '$contacts');";
        $sql_insert_user_query = mysqli_query($db_connection, $sql_insert_user);
        header('Location:/index.php');
        die();
    }
}

$layout = include_template('sign-up_layout.php', [
    'title' => 'Регистрация пользователя',
    'categories' => $categories,
    'errors' => $errors,
    'warning_about_errors' => $warning_about_errors
]);
print($layout);
