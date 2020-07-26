<?php
require_once('helpers.php');

//Connection with database
$db_connection = mysqli_connect('localhost', 'root', 'root', "yeticave");
mysqli_set_charset($db_connection, "utf8");
if ($db_connection == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

//Check if query parameters exists
if (!isset($_GET['id'])) {
    return404();
}
$id = intval($_GET['id']);

$sql_id = "SELECT id FROM lot WHERE id=" . $id;
$sql_id_query = mysqli_query($db_connection, $sql_id);

if (mysqli_num_rows($sql_id_query) == 0) {
    return404();
}

// Extract categories from database
$sql_category = "SELECT name FROM category;";

$sql_category_query = mysqli_query($db_connection, $sql_category);

$categories = [];
while ($category = mysqli_fetch_array($sql_category_query, MYSQLI_ASSOC)) {
    array_push($categories, $category['name']);
}

// Extract necesary info about lots from database
$sql_lot = "SELECT lot.name as lot_name, lot.description as lot_description, lot.init_price as lot_init_price, lot.final_date as lot_final_date,
bid.bid_value as lot_bid_value, user.name as bid_username, bid.bid_time as bid_time_insert, category.name as lot_category,
lot_img.image_url as lot_image_url FROM lot
LEFT JOIN bid ON lot.id = bid.lot_id
LEFT JOIN lot_img ON lot.id = lot_img.lot_id
LEFT JOIN user ON  user.id = bid.user_id
LEFT JOIN category ON category.id = lot.category_id WHERE lot.id = " . $id;

$sql_lot_query = mysqli_query($db_connection, $sql_lot);
$lot = mysqli_fetch_array($sql_lot_query, MYSQLI_ASSOC);

// Extract necesary info about bids from database
$sql_bids = "SELECT user.name as username, bid.bid_value, bid.bid_time FROM bid LEFT JOIN  user ON user.id = bid.user_id WHERE bid.lot_id = " . $id;

$sql_bids_query = mysqli_query($db_connection, $sql_bids);
$bids = mysqli_fetch_all($sql_bids_query, MYSQLI_ASSOC);

//Count of bids for the lot
$sql_count_of_bids = "SELECT COUNT(*) as count FROM bid WHERE lot_id = " . $id;
$sql_count_of_bids_query = mysqli_query($db_connection, $sql_count_of_bids);
$bids_count = mysqli_fetch_assoc($sql_count_of_bids_query);
$count = $bids_count['count'];


// Display template lot_layout
$lot_layout = include_template('lot_layout.php', ['categories' => $categories, 'lot_name' => $lot['lot_name'],
    'image_url' => $lot['lot_image_url'], 'category_id' => $lot['lot_category'], 'description' => $lot['lot_description'],
    'init_price' => $lot['lot_init_price'], 'bid_value' => $lot['lot_bid_value'], 'expiration_date' => $lot['lot_final_date'],
    'bids' => $bids, 'bids_count' => $count]);
echo $lot_layout;


