<?php
require_once('helpers.php');
require_once('init.php');
/* Подключение к БД */
$db_connection = mysqli_connect('localhost', 'root', 'root', "yeticave");
mysqli_set_charset($db_connection, "utf8");
if ($db_connection == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

/* SQL-запрос для получения списка категорий */
$sql_categories = "SELECT name FROM category;";
$categories_result = mysqli_query($db_connection, $sql_categories);
if (!$categories_result) {
    $error = mysqli_error($db_connection);
    print("Ошибка MySQL: " . $error);
}
$categories = [];
while ($category = mysqli_fetch_array($categories_result, MYSQLI_ASSOC)) {
    array_push($categories, $category['name']);
}
$sql_all_lots_from_category = "SELECT lot.name, lot.final_date, lot.id, lot.description, lot.init_price, category.name AS category_name,
lot_img.image_url FROM lot JOIN category ON lot.category_id = category.id JOIN lot_img ON lot.id = lot_img.lot_id
WHERE category.name = '" . mysqli_real_escape_string($db_connection, $_GET['category']) . "';";
$sql_all_lots_from_category_query = mysqli_query($db_connection, $sql_all_lots_from_category);
$all_lots = mysqli_fetch_all($sql_all_lots_from_category_query, MYSQLI_ASSOC);


$layout = include_template('all-lots_layout.php', ['categories' => $categories, 'user_name' => htmlspecialchars($_SESSION['user']['name']),
    'all_lots' => $all_lots]);
print($layout);
