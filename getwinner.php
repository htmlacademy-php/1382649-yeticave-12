<?php
require_once('vendor/autoload.php');
require_once('db_connection.php');

$transport = new Swift_SmtpTransport('smtp.mailtrap.io', 2525);
$transport->setUsername('7846549fa28176');
$transport->setPassword('774a73ead5dd48');

$mailer = new Swift_Mailer($transport);

$expired_lots_sql_query = mysqli_query($db_connection, "SELECT id, name from lot WHERE final_date< NOW();");
$expired_lots = mysqli_fetch_all($expired_lots_sql_query, MYSQLI_ASSOC);
$winners = [];
foreach ($expired_lots as $lots) {
    $winners_sql_query = mysqli_query($db_connection, "SELECT user_id, user.name AS user_name, user.email, lot.id as lot_id, lot.name AS lot_name FROM bid
LEFT JOIN user ON bid.user_id = user.id
LEFT JOIN lot ON lot.id = bid.lot_id
WHERE lot_id = " . mysqli_real_escape_string($db_connection, $lots['id']) . " ORDER BY bid_value DESC LIMIT 1");
    $winner = mysqli_fetch_array($winners_sql_query, MYSQLI_ASSOC);
    if ($winner != null) {
        array_push($winners, $winner);
    }
}
foreach ($winners as $winner) {
    $message = new Swift_Message();
    $message->setSubject("Ваша ставка победила");
    $message->setFrom('keks@phpdemo.ru');
    $message->setBcc([$winner['email'] => $winner['user_name']]);
    $content = include_template("email.php", ['winner' => $winner]);
    $message->setBody($content, 'text/html');
    $failures = [];
    $result = $mailer->send($message, $failures);
    if ($result) {
        print("Рассылка успешно отправлена");
    } else {
        print("Не удалось отправить рассылку");
    }
}
