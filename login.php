<?php
require_once('helpers.php');
require_once('init.php');
$db_connection = mysqli_connect('localhost', 'root', 'root', 'yeticave');
mysqli_set_charset($db_connection, 'utf8');
if ($db_connection == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

$sql_categorie_query = mysqli_query($db_connection, "SELECT name FROM category");
$categories = [];
while ($category = mysqli_fetch_array($sql_categorie_query, MYSQLI_ASSOC)) {
    array_push($categories, $category['name']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    $rules = [
        'email' => function () use ($db_connection) {
            return verifyEmail($_POST['email'], $db_connection);
        },
        'password' => function () {
            return validatePassword($_POST['password']);
        }
    ];
    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }
    $errors = array_filter($errors);

    $email = mysqli_real_escape_string($db_connection, $_POST['email']);
    $sql_verify_email = "SELECT * FROM user WHERE email ='" . $email . "'";
    $sql_verify_email_query = mysqli_query($db_connection, $sql_verify_email);
    $user = $sql_verify_email_query ? mysqli_fetch_array($sql_verify_email_query, MYSQLI_ASSOC) : null;

    if (count($errors) == 0 && $user) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    }
    if($_POST['email']!=$user['email']) {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (!count($errors)) {
        header("Location:/index.php");
        exit();
    }
}


$layout = include_template('login_layout.php', ['categories' => $categories, 'errors' => $errors]);
print($layout);
?>
