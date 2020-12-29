<?php
require_once('init.php');
require_once('helpers.php');
require_once('functions.php');
require_once('db_connection.php');

$sql_category = "SELECT name FROM category;";
$sql_category_query = mysqli_query($db_connection, $sql_category);
$categories = [];
$user_bets = [];

while ($category = mysqli_fetch_array($sql_category_query, MYSQLI_ASSOC)) {
    array_push($categories, $category['name']);
}

if (isset($_SESSION['user']['id'])) {
    $sql_bets = "SELECT bid.user_id as user_id, bid.lot_id as lot_id, lot_img.image_url as img_url, category.name as category, lot.name as lot_name,
       lot.final_date as fin_data, bid.bid_time as bid_time, bid.bid_value as price FROM bid
           LEFT JOIN lot ON  bid.lot_id=lot.id
           LEFT JOIN lot_img ON bid.lot_id=lot_img.lot_id
           LEFT JOIN category ON lot.category_id = category.id
WHERE bid.user_id=" . $_SESSION['user']['id'];
    $sql_bets_query = mysqli_query($db_connection, $sql_bets);
    $user_bets = mysqli_fetch_all($sql_bets_query, MYSQLI_ASSOC);
}

$user_name = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : null;
$content = include_template('my-bets_layout.php', [
    'categories' => $categories,
    'username' => $user_name,
    'user_bets' => $user_bets
]);
print($content);
