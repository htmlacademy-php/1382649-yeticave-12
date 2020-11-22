<?php
require_once('helpers.php');
require_once('init.php');
$db_connection = mysqli_connect('localhost', 'root', 'root', "yeticave");
mysqli_set_charset($db_connection, "utf8");
if ($db_connection == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

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
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$number_of_lots_by_page = 1;
$sql_count_of_lots = mysqli_query($db_connection, "SELECT COUNT(*) as cnt_of_lots from lot LEFT JOIN category ON lot.category_id = category.id
WHERE category.name = '" . $_GET['category'] . "' AND lot.final_date > NOW()");
$count_of_lots_by_category = mysqli_fetch_assoc($sql_count_of_lots) ['cnt_of_lots'];
$number_of_pages = ceil($count_of_lots_by_category / $number_of_lots_by_page);
$offset = ($current_page - 1) * $number_of_lots_by_page;
$array_of_pages = range(1, $number_of_pages);

$sql_all_lots_from_category = "SELECT lot.name, lot.final_date, lot.id, lot.description, lot.init_price, category.name AS category_name,
lot_img.image_url FROM lot JOIN category ON lot.category_id = category.id JOIN lot_img ON lot.id = lot_img.lot_id
WHERE category.name = '" . mysqli_real_escape_string($db_connection, $_GET['category']) . "' AND lot . final_date > NOW() LIMIT " . $number_of_lots_by_page . " OFFSET " .
    $offset . ";";
$sql_all_lots_from_category_query = mysqli_query($db_connection, $sql_all_lots_from_category);
$all_lots = mysqli_fetch_all($sql_all_lots_from_category_query, MYSQLI_ASSOC);

$layout = include_template('all-lots_layout.php', ['categories' => $categories, 'user_name' => $_SESSION['user']['name'],
    'all_lots' => $all_lots, 'number_of_pages' => $number_of_pages, 'current_page' => $current_page, 'array_of_pages' => $array_of_pages]);
print($layout);
