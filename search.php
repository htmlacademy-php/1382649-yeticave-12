<?php
require_once "helpers.php";
require_once "init.php";

$db_connection = mysqli_connect('localhost', 'root', 'root', 'yeticave');
mysqli_set_charset($db_connection, 'utf-8');

if ($db_connection == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}
$sql_categories = mysqli_query($db_connection, "SELECT name FROM category;");
$categories = [];
while ($category = mysqli_fetch_array($sql_categories, MYSQLI_ASSOC)) {
    array_push($categories, $category['name']);
}

if (isset($_GET['search'])) {
    $search = $_GET['search'] != null ? trim($_GET['search']) : '';
    $search_array = explode(' ', $search);
    $search_for_sql = '';
    $search_error = "Ничего не найдено по вашему запросу";

    foreach ($search_array as $element) {
        $search_for_sql = $search_for_sql . $element . '* ';
    }
    $search_for_sql = trim($search_for_sql);

    $sql_lot = "SELECT lot.id, lot.name as lot_name, lot.init_price as lot_init_price, lot.final_date as lot_final_date,
category.name as lot_category,
lot_img.image_url as lot_image_url FROM lot
LEFT JOIN lot_img ON lot.id = lot_img.lot_id
LEFT JOIN category ON category.id = lot.category_id
WHERE MATCH(lot.name, lot.description) AGAINST('" . mysqli_real_escape_string($db_connection, $search_for_sql) . "' IN BOOLEAN MODE) ORDER BY id DESC";

    $search_result = '';
    if ($search != null) {
        $sql_lot_query = mysqli_query($db_connection, $sql_lot);
        $search_result = mysqli_fetch_all($sql_lot_query, MYSQLI_ASSOC);
    }
} else {
    $search_error = "Введите ключевое слово для поиска";
}

$layout = include_template('search_layout.php', ['categories' => $categories,
    'user_name' => $_SESSION['user']['name'], 'search_result' => $search_result, 'search_error' => $search_error]);
print $layout;
?>
