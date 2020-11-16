<?php
require_once('init.php');
require_once('helpers.php');

$db_connection = mysqli_connect('localhost', 'root', 'root', 'yeticave');
mysqli_set_charset($db_connection, "utf8");

if ($db_connection == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

$sql_category = "SELECT name FROM category;";
$sql_category_query = mysqli_query($db_connection, $sql_category);
$categories = [];
while ($category = mysqli_fetch_array($sql_category_query, MYSQLI_ASSOC)) {
    array_push($categories, $category['name']);
}

$sql_bets = "SELECT bid.user_id as user_id, bid.lot_id as lot_id, lot_img.image_url as img_url, category.name as category, lot.name as lot_name,
       lot.final_date as fin_data, bid.bid_time as bid_time, bid.bid_value as price FROM bid
           LEFT JOIN lot ON  bid.lot_id=lot.id
           LEFT JOIN lot_img ON bid.lot_id=lot_img.lot_id
           LEFT JOIN category ON lot.category_id = category.id
WHERE bid.user_id=" . $_SESSION['user']['id'];
$sql_bets_query = mysqli_query($db_connection, $sql_bets);
$user_bets = mysqli_fetch_all($sql_bets_query, MYSQLI_ASSOC);
echo $sql_bets;
$content = include_template('my-bets_layout.php', ['categories' => $categories, 'username' => $_SESSION['user']['name'],
    'user_bets' => $user_bets]);
print($content);
