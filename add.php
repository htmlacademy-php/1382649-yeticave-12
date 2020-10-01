<?php
require_once("helpers.php");
require_once("init.php");
if ($_SESSION['user']['name'] == null) {
    header('HTTP/1.0 403 Forbidden');
    echo "<h1>Error 403</h1>";
    echo "Для ввода нового лота вы должны авторизоваться!";
    echo "<br><a href = 'sign-up.php'>Зарегистрируйтесь</a> или <a href = 'login.php'>войдите в свой аккаунт</a>";
    exit();
}

$db_connection = mysqli_connect('localhost', 'root', 'root', 'yeticave');
mysqli_set_charset($db_connection, 'utf-8');
if ($db_connection == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

$sql_categories = "SELECT name FROM category;";
$sql_categories_query = mysqli_query($db_connection, $sql_categories);
$categories = [];
while ($category = mysqli_fetch_array($sql_categories_query, MYSQLI_ASSOC)) {
    array_push($categories, $category['name']);
}

$warning_about_errors = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required_fields = ['lot-name', 'category', 'message', 'lot-image', 'lot-price', 'lot-step', 'lot-date'];
    $errors = [];

    $rules = [
        'lot-name' => function () {
            return validateText('lot-name', "Введите наименование лота", 50);
        },
        'category' => function () use ($categories) {
            return validateIssetCategory($categories, $_POST['category']);
        },
        'message' => function () {
            return validateText("message", "Напишите описание лота", 200);
        },
        'lot-image' => function () {
            return validateImage('lot-image');
        },
        'lot-rate' => function () {
            return validatePrice();
        },
        'lot-step' => function () {
            return validateStep();
        },
        'lot-date' => function () {
            return validateDate();
        }
    ];


    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    if (isset($rules['lot-image'])) {
        $rule = $rules['lot-image'];
        $errors['lot-image'] = $rule();
    }
    $errors = array_filter($errors);

    $sql_selected_category = "SELECT id FROM category WHERE name ='" . $_POST['category'] . '\'';
    $selected_category_query = mysqli_query($db_connection, $sql_selected_category);
    $selected_category = mysqli_fetch_array($selected_category_query, MYSQLI_ASSOC);
    $category_id = $selected_category['id'];

    if (empty($errors)) {
        $safe_lot_name = mysqli_real_escape_string($db_connection, $_POST['lot-name']);
        $safe_category_id = intval($category_id);
        $safe_message = mysqli_real_escape_string($db_connection, $_POST['message']);
        $safe_lot_rate = mysqli_real_escape_string($db_connection, $_POST['lot-rate']);
        $safe_lot_step = mysqli_real_escape_string($db_connection, $_POST['lot-step']);
        $safe_lot_date = mysqli_real_escape_string($db_connection, $_POST['lot-date']);

        $sql_lot_insert = "INSERT INTO lot (name, category_id, description, init_price, step, final_date)
        VALUES ('" . $safe_lot_name . "', " . "'" . $safe_category_id . "', '" . $safe_message . "', '" .
            $safe_lot_rate . "', '" . $safe_lot_step . "', '" . $safe_lot_date . "');";

        $sql_lot_insert_query = mysqli_query($db_connection, $sql_lot_insert);
        $last_id = mysqli_insert_id($db_connection);
        if (isset($_FILES['lot-image'])) {
            $file_name = $_FILES['lot-image']['name'];
            $file_path = __DIR__ . '/uploads/';
            $file_url = '/uploads/' . $file_name;

            move_uploaded_file($_FILES['lot-image']['tmp_name'], $file_path . $file_name);

            $sql_insert_lot_image = "INSERT INTO lot_img (image_url, lot_id) VALUES ('" . $file_url . "' , '" . $last_id . "');";
            $sql_image_insert_query = mysqli_query($db_connection, $sql_insert_lot_image);
        }


        if (!empty($errors)) {
            $warning_about_errors = "Пожалуйста, исправьте ошибки в форме";
        }

        header('Location:/lot.php?id=' . $last_id);
        die();
    }
}

$layout = include_template('add-lot.php', ['title' => 'Добавление лота', 'username' => $_SESSION['user']['name'],
    'categories' => $categories, 'errors' => $errors, 'warning_about_errors' => $warning_about_errors]);
print $layout;

?>
