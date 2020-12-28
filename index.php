<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');
require_once('getwinner.php');
require_once('db_connection.php');

$sql_categories = "SELECT name FROM category;";
$categories_result = mysqli_query($db_connection, $sql_categories);
if (!$categories_result) {
    $error = mysqli_error($db_connection);
    print("Ошибка MySQL: " . $error);
    die();
}
$categories = [];
while ($category = mysqli_fetch_array($categories_result, MYSQLI_ASSOC)) {
    array_push($categories, $category['name']);
}

$sql_lots = "SELECT lot.id, lot.name as name, init_price as price, image_url as url, category.name as category, final_date as expiration_date
FROM lot
         LEFT JOIN lot_img ON lot.id = lot_img.lot_id
         LEFT JOIN category ON category.id = lot.category_id
WHERE lot.final_date > NOW()
ORDER BY lot.id DESC
LIMIT 6;";
$lots_result = mysqli_query($db_connection, $sql_lots);
if (!$lots_result) {
    $error = mysqli_error($db_connection);
    print("Ошибка MySQL: " . $error);
    die();
}

$user_name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : null;
$lots = mysqli_fetch_all($lots_result, MYSQLI_ASSOC);
$main = include_template('main.php', ['categories' => $categories, 'announces' => $lots]);
$content = include_template('layout.php', [
    'content' => $main,
    'user_name' => $user_name,
    'title' => 'Главная',
    'categories' => $categories
]);
print ($content);
?>