<?php
require_once('helpers.php');
require_once('init.php');

$db_connection = mysqli_connect('localhost', 'root', 'root', "yeticave");
mysqli_set_charset($db_connection, "utf8");
if ($db_connection == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

if (!isset($_GET['id'])) {
    return404();
}
$id = intval($_GET['id']);
$sql_id = "SELECT id FROM lot WHERE id=" . $id;
$sql_id_query = mysqli_query($db_connection, $sql_id);

if (mysqli_num_rows($sql_id_query) == 0) {
    return404();
}

$sql_category = "SELECT name FROM category;";
$sql_category_query = mysqli_query($db_connection, $sql_category);

$categories = [];
while ($category = mysqli_fetch_array($sql_category_query, MYSQLI_ASSOC)) {
    array_push($categories, $category['name']);
}

$sql_lot = "SELECT lot.name as lot_name, lot.step as lot_step, lot.description as lot_description, lot.init_price as lot_init_price, lot.final_date as lot_final_date,
bid.bid_value as lot_bid_value, user.name as bid_username, bid.bid_time as bid_time_insert, category.name as lot_category,
lot_img.image_url as lot_image_url FROM lot
LEFT JOIN bid ON lot.id = bid.lot_id
LEFT JOIN lot_img ON lot.id = lot_img.lot_id
LEFT JOIN user ON  user.id = bid.user_id
LEFT JOIN category ON category.id = lot.category_id WHERE lot.id = " . $id;

$sql_lot_query = mysqli_query($db_connection, $sql_lot);
$lot = mysqli_fetch_array($sql_lot_query, MYSQLI_ASSOC);

$sql_bids = "SELECT user.name as username, bid.bid_value, DATE_FORMAT(bid.bid_time, \"%d.%m.%y\") AS bid_data,
       DATE_FORMAT(bid.bid_time, \"%m:%i\") AS bid_hour FROM bid LEFT JOIN  user ON user.id = bid.user_id WHERE bid.lot_id = " . $id;

$sql_bids_query = mysqli_query($db_connection, $sql_bids);
$bids = mysqli_fetch_all($sql_bids_query, MYSQLI_ASSOC);

$sql_count_of_bids = "SELECT COUNT(*) as count FROM bid WHERE lot_id = " . $id;
$sql_count_of_bids_query = mysqli_query($db_connection, $sql_count_of_bids);
$bids_count = mysqli_fetch_assoc($sql_count_of_bids_query);
$count = $bids_count['count'];

$sql_last_bid = "SELECT MAX(bid_value) AS max_bid_value FROM bid WHERE lot_id = " . $_GET['id'];
$sql_last_bid_query = mysqli_query($db_connection, $sql_last_bid);
$last_bid_value = mysqli_fetch_array($sql_last_bid_query);
if ($last_bid_value[0] == 0) {
    $min_bid_value = $lot['lot_init_price'];
} else {
    $min_bid_value = $last_bid_value[0] + $lot['lot_step'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = null;
    if ($_POST['cost'] < $min_bid_value) {
        $error = 'Мин. ставка должно быть ' . $min_bid_value . ' p';
    } else if (empty($_POST['cost'])) {
        $error = 'Введите ставку';
    } else {
        $sql_insert_new_bid = "INSERT INTO bid (user_id, lot_id, bid_value, bid_time)
VALUES ('" . $_SESSION['user']['id'] . "', '" . $_GET['id'] . "', '" . $_POST['cost'] . "', '" . date('Y-m-d h-i-s') . "');";
        $sql_insert_new_bid_query = mysqli_query($db_connection, $sql_insert_new_bid);
        page_redirect("lot.php?id=" . $_GET['id']);
    }
}
$expired_lot = 'Истекший лот';

$lot_layout = include_template('lot_layout.php', ['categories' => $categories, 'lot_name' => $lot['lot_name'],
    'image_url' => $lot['lot_image_url'], 'category_id' => $lot['lot_category'], 'description' => $lot['lot_description'],
    'init_price' => $lot['lot_init_price'], 'bid_value' => $lot['lot_bid_value'], 'expiration_date' => $lot['lot_final_date'],
    'bids' => $bids, 'bids_count' => $count, 'user_name' => $_SESSION['user']['name'], 'last_bid_value' => $last_bid_value[0],
    'min_bid_value' => $min_bid_value, 'error' => $error]);
echo $lot_layout;
