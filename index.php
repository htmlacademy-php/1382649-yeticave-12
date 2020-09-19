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

/* SQL-запрос для получения списка новых лотов */
$sql_lots = "SELECT lot.id, lot.name as name, init_price as price, image_url as url, bid_value, category.name as category, final_date as expiration_date
FROM lot
         LEFT JOIN lot_img ON lot.id = lot_img.lot_id
         LEFT JOIN bid ON bid.lot_id = lot.id
         LEFT JOIN category ON category.id = lot.category_id
WHERE lot.final_date > NOW()
ORDER BY lot.id DESC
LIMIT 6;";
$lots_result = mysqli_query($db_connection, $sql_lots);
if (!$lots_result) {
    $error = mysqli_error($db_connection);
    print("Ошибка MySQL: " . $error);
}
$lots = mysqli_fetch_all($lots_result, MYSQLI_ASSOC);
$main = include_template('main.php', ['categories' => $categories, 'announces' => $lots]);
$content = include_template('layout.php', ['content' => $main, 'user_name' => htmlspecialchars($_SESSION['user']['name']), 'title' => 'Главная',
    'categories' => $categories]);
print ($content);
?>
