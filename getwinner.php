<?php
require_once('vendor/autoload.php');
require_once('db_connection.php');
require_once('init.php');

$transport = new Swift_SmtpTransport('smtp.mailtrap.io', 2525);
$transport->setUsername('7846549fa28176');
$transport->setPassword('774a73ead5dd48');

$mailer = new Swift_Mailer($transport);

$expired_lot_sql_query = mysqli_query(
    $db_connection,
    "SELECT id, name from lot WHERE final_date< NOW() AND closed = 0 LIMIT 1;"
);
$expired_lot = mysqli_fetch_array($expired_lot_sql_query, MYSQLI_ASSOC);

if ($expired_lot !== null) {
    $winner_sql = "SELECT user_id, user.name AS user_name, user.email, lot.id as lot_id, lot.name AS lot_name FROM bid
LEFT JOIN user ON bid.user_id = user.id
LEFT JOIN lot ON lot.id = bid.lot_id
WHERE lot_id = " . mysqli_real_escape_string($db_connection, $expired_lot['id']) . " ORDER BY bid_value DESC LIMIT 1";

    $winner_sql_query = mysqli_query($db_connection, $winner_sql);

    $winner = mysqli_fetch_array($winner_sql_query, MYSQLI_ASSOC);

    $update_column_closed_sql_query = mysqli_query(
        $db_connection,
        "UPDATE lot SET closed = 1 WHERE id = " . $expired_lot['id']
    );

    $message = new Swift_Message();
    $message->setSubject("Ваша ставка победила");
    $message->setFrom('keks@phpdemo.ru');
    $message->setBcc([$winner['email'] => $winner['user_name']]);
    $content = include_template("email.php", ['winner' => $winner]);
    $message->setBody($content, 'text/html');
    $failures = [];
    $result = $mailer->send($message, $failures);
}
