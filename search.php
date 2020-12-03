<?php
require_once "helpers.php";
require_once('functions.php');
require_once "init.php";
require_once "db_connection.php";
$sql_categories = mysqli_query($db_connection, "SELECT name FROM category;");
$categories = [];
while ($category = mysqli_fetch_array($sql_categories, MYSQLI_ASSOC)) {
    array_push($categories, $category['name']);
}
$search_result = [];
$number_of_pages = 0;
$curent_page = 0;
$pages = 0;

if (!empty($_GET['search'])) {
    $search = trim($_GET['search']);
    $search_array = explode(' ', $search);
    $search_for_sql = '';
    $search_error = "Ничего не найдено по вашему запросу";

    foreach ($search_array as $element) {
        $search_for_sql = $search_for_sql . $element . '* ';
    }
    $search_for_sql = trim($search_for_sql);
    $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
    $search_for_sql_whitout_symbols = str_replace($reservedSymbols, '', $search_for_sql);

    $curent_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $items_on_page = 6;
    $sql_lots_count = "SELECT COUNT(*) as lots_count FROM lot
WHERE MATCH(lot.name, lot.description) AGAINST('" . mysqli_real_escape_string($db_connection, $search_for_sql_whitout_symbols) .
        "' IN BOOLEAN MODE);";
    $sql_lots_count_query = mysqli_query($db_connection, $sql_lots_count);
    $lots_count = mysqli_fetch_assoc($sql_lots_count_query)['lots_count'];
    $number_of_pages = ceil($lots_count / $items_on_page);
    $offset = ($curent_page - 1) * $items_on_page;
    $pages = range(1, $number_of_pages);

    $sql_lot = "SELECT lot.id, lot.name as lot_name, lot.init_price as lot_init_price, lot.final_date as lot_final_date,
                category.name as lot_category,lot_img.image_url as lot_image_url FROM lot
                LEFT JOIN lot_img ON lot.id = lot_img.lot_id
                LEFT JOIN category ON category.id = lot.category_id
                WHERE MATCH(lot.name, lot.description) AGAINST('" . mysqli_real_escape_string($db_connection, $search_for_sql_whitout_symbols) .
        "' IN BOOLEAN MODE) ORDER BY id DESC LIMIT " . $items_on_page . " OFFSET " . $offset . ";";
    $search_result = '';
    if ($search != null) {
        $sql_lot_query = mysqli_query($db_connection, $sql_lot);
        $search_result = mysqli_fetch_all($sql_lot_query, MYSQLI_ASSOC);
    }
} else {
    $search_error = "Введите ключевое слово для поиска";
}
$user_name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : null;
$layout = include_template('search_layout.php', ['categories' => $categories,
    'user_name' => $user_name, 'search_result' => $search_result, 'search_error' => $search_error,
    'number_of_pages' => $number_of_pages, 'curent_page' => $curent_page, 'pages' => $pages]);
print $layout;
?>
