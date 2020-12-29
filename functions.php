<?php
require_once('db_connection.php');

/**
 * Форматирует цену лота
 *
 * @param $price - цена лота
 *
 * @return string - возвращает отформатированную сумму вместе со знаком рубля.
 */
function formatting_prices($price)
{
    $price = ceil($price);

    if ($price >= 1000) {
        $price = number_format($price, 0, ',', ' ');
    }

    $amount = $price . ' <img src="img\rub.svg">';

    return $amount;
}

/**
 * Возвращает оставшееся время до даты из будущего в формате «ЧЧ:ММ»
 *
 * @param $expiration_date - дата в формате ГГГГ-ММ-ДД
 *
 * @return array - возвращает часы, минуты
 */
function get_dt_range($expiration_date)
{
    $dt_now_timestamp = strtotime("now");
    $dt_expiration_timestamp = strtotime($expiration_date);
    $remaining_time_timestamp = $dt_expiration_timestamp - $dt_now_timestamp;
    $remaining_hours = floor($remaining_time_timestamp / 3600);
    $remaining_minutes = floor(($remaining_time_timestamp % 3600) / 60);

    return [$remaining_hours, $remaining_minutes];
}

/**
 *  Позволяет узнать, что осталось меньше часа до истечения лота. Оставшееся время меньше часа меняет цвет
 *
 * @param $expiration_date - оставшееся время до истечения лота
 */
function remaining_time($expiration_date)
{
    $remaining_time = get_dt_range($expiration_date);
    if ($remaining_time[0] >= '1') {
        echo '<div class="lot__timer timer">';
        echo $remaining_time[0] . ':' . $remaining_time[1];
        echo '</div>';
    }
    else {
        echo '<div class="timer--finishing timer">';
        echo $remaining_time[0] . ':' . $remaining_time[1];
        echo '</div>';
    }
}

/**
 *Возвращает страницу с ошибкой 404 если если запрошенная страница не была найдена
 */
function return404()
{
    global $db_connection;
    header('Status: 404', true, 404);
    include __DIR__ . '/404.php';
    die();
}

/**
 * Возвращает значение из переменной POST
 *
 * @param $name - название поля
 *
 * @return mixed|string - значение поля
 */
function getPostVal($name)
{
    return isset($_POST[$name]) ? $_POST[$name] : '';
}

/**
 * Проверяет правильность введенного текста
 *
 * @param $name - название поля
 * @param $error_description - текст ошибки
 * @param $max_val_of_char - максимальное количество символов
 *
 * @return string|null - возвращает текст ошибки если поле пустое, слишком много символов  или все правильно
 */
function validateText($name, $error_description, $max_val_of_char)
{
    if (empty($_POST[$name])) {
        return $error_description;
    }
    if ($result = isCorrectLength($name, 1, $max_val_of_char)) {
        return $result;
    }
    return null;
}

/**
 * Проверяет имеет ли введенное значение правильную длину
 *
 * @param $name - введенный текс
 * @param $min - минимальное число символов
 * @param $max - максимальное число символов
 *
 * @return string|null - возвращает текст ошибки или null если все правильно
 */
function isCorrectLength($name, $min, $max)
{
    $len = strlen($_POST[$name]);

    if ($len < $min or $len > $max) {
        return "Значение должно быть до $max символов";
    }
    return null;
}

/**
 * Проверяет установлена ли категория
 *
 * @param $categories - массив категорий
 * @param $category - категория
 *
 * @return string|null - возвращает текст ошибки или null если все правильно
 */
function validateIssetCategory($categories, $category)
{
    if (!in_array($category, $categories)) {
        return "Выберите категорию";
    }
    return null;
}

/**
 * Проверяет изображение на правильности
 *
 * @param $name - имя поля
 *
 * @return string|null - возвращает текст ошибки или null если все правильно
 */
function validateImage($name)
{
    if ($_FILES[$name]['size'] === 0) {
        return "Загрузите картинку";
    }
    if (isset($_FILES[$name])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_name = $_FILES[$name]['tmp_name'];
        $file_size = $_FILES[$name]['size'];
        $file_type = finfo_file($finfo, $file_name);

        if ($file_type !== 'image/jpg' && $file_type !== 'image/jpeg') {
            return "Загрузите картинку в формате JPG/JPEG";
        }
        if ($file_size > 200000) {
            return "Максимальный размер файла: 200Кб";
        }
    }

    return null;
}

/**
 * Валидирует введенную цену
 *
 * @return string|null - возвращает текст ошибки или null если все правильно
 */
function validatePrice()
{
    if (intval($_POST['lot-rate']) <= 0) {
        return "Введите начальную цену";
    }
    return null;
}

/**
 * Валидирует введенный шаг ставки
 *
 * @return string|null - возвращает текст ошибки или null если все правильно
 */
function validateStep()
{
    if (intval($_POST['lot-step']) <= 0) {
        return "Введите шаг ставки";
    }
    return null;
}

/**
 * Конвертирует дату в Timestamp
 *
 * @return false|int - возвращает дату в timestamp
 */
function convertDataToTimestamp()
{
    $post_date = $_POST['lot-date'];
    return $timestamp_post_date = strtotime($post_date);
}

/**
 * Валидирует введенную дату
 *
 * @return string|null - возвращает текст ошибки или null если все правильно
 */
function validateDate()
{
    $set_date = convertDataToTimestamp();
    $current_date = date('Y-m-d');
    $current_data_timestamp = strtotime($current_date);
    if (empty($_POST['lot-date'])) {
        return "Введите дату";
    }
    if ($set_date <= $current_data_timestamp) {
        return "Дата не может быть из прошлого или сегодняшней";
    }

    return null;
}

/**
 *  Валидирует введенный емайл
 *
 * @param $email - текст емайла
 * @param $db_connection - подключение к базу данных
 *
 * @return string|null - возвращает текст ошибки или null если все правильно
 */
function validateEmail($email, $db_connection)
{
    $sanitized_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (empty($sanitized_email)) {
        return "Введите адрес электронной почты";
    }

    if (!filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)) {
        return 'Вы ввели неправильный адрес электронной почты';
    }

    $sql_compare_email = "SELECT email FROM user WHERE email ='" . mysqli_real_escape_string($db_connection, $sanitized_email) . "';";
    $sql_compare_email_query = mysqli_query($db_connection, $sql_compare_email);
    $sql_email_comparation_result = mysqli_fetch_array($sql_compare_email_query, MYSQLI_NUM);
    if ($sql_email_comparation_result !== null) {
        return 'Такой адрес электронной почты уже существует';
    }

    return null;
}

/**
 * Валидирует введенный пароль
 *
 * @param $password - текст пароля
 *
 * @return string - возвращает текст ошибки
 */
function validatePassword($password)
{
    if (strlen($password) === 0) {
        return "Введите пароль";
    }
    if (strlen($password) < 8) {
        return "Пароль должен иметь минимум 8 символов";
    }
}

/**
 * Валидирует введенное имя пользователя
 *
 * @param $name - имя пользователя
 * @param $max_nr_of_simbols - максимальное количество символов
 *
 * @return string|null - возвращает текст ошибки или null если все правильно
 */
function validateName($name, $max_nr_of_simbols)
{
    if (empty($name)) {
        return "Введите имя";
    }
    if (strlen($name) > $max_nr_of_simbols) {
        return "Значение должно быть до $max_nr_of_simbols символов";
    }
    return null;
}

/**
 * Валидирует введенные контакты
 *
 * @param $contacts - введенный текст
 * @param $max_nr_of_symbols - максимальное количество символов
 *
 * @return string|null - возвращает текст ошибки или null если все правильно
 */
function validateContacts($contacts, $max_nr_of_symbols)
{
    if (empty($contacts)) {
        return "Напишите как с вами связаться";
    }
    if (strlen($contacts) > $max_nr_of_symbols) {
        return "Значение должно быть до $max_nr_of_symbols символов";
    }
    return null;
}

/**
 * Проверяет если был введен адрес электронной почты
 *
 * @param $email - введенный текст электронной почты
 * @param $db_connection - подключение к базу данных
 *
 * @return string|null - возвращает текст ошибки или null если все правильно
 */
function verifyEmail($email, $db_connection)
{
    if (empty($email)) {
        return "Введите адрес электронной почты";
    }
    return null;
}

/**
 * Завершает сесию
 */
function logout()
{
    session_start();
    $_SESSION = [];
}

/**
 * Генеирует новый  URL. Предназначена для пагинации.
 *
 * @param $name - имя параметра  URL
 * @param $value - значение параметра  URL
 *
 * @return  - возвращает новый URL
 */
function addOrUpdateUrlParam($name, $value)
{
    $params = $_GET;
    $params[$name] = $value;
    return $_SERVER['SCRIPT_NAME'] . '?' . http_build_query($params);
}

/**
 * Делает редирект на другую указанную страницу
 *
 * @param $location - указивает на какую страницу
 */
function page_redirect($location)
{
    header("Location:" . $location);
    die;
}

/**
 * Используется в других функции для инициализации стилей
 *
 * @param $line_background_style - стиль CSS 'line_background_style'
 * @param $column_background_style - стиль CSS 'column_background_style'
 * @param $text - стиль CSS 'text'
 * @param $addres - стиль CSS 'addres'
 *
 * @return array - возвращает массив из стилей
 */
function remaining_time_bet_array_values($line_background_style, $column_background_style, $text, $addres)
{
    $values = [
        'line_background_style' => $line_background_style,
        'column_background_style' => $column_background_style,
        'text' => $text,
        'addres' => $addres
    ];
    return $values;
}

/**
 * Показывает время, оставшееся до завершения акции или выигранных лотов
 *
 * @param $expiration_time - время до оканчания акции
 * @param $user_id - ид пользователя
 * @param $user_lot - лот пользователя
 *
 * @return array возвращает массив информации соответствующий лоту
 */
function remaining_time_bet($expiration_time, $user_id, $user_lot)
{
    $winner = winner_user($user_lot, $user_id, $expiration_time);
    $time = get_dt_range($expiration_time);
    if ($winner === 1) {
        return remaining_time_bet_array_values(
            'rates__item rates__item--win',
            'timer timer--win',
            'Ставка выиграла', 'Телефон +7 900 667-84-48, Скайп: Vlas92. Звонить с 14 до 20');
    }
    if ($time[0] > 24) {
        return remaining_time_bet_array_values(
            'rates__item',
            'timer',
            $time[0] . ':' . $time[1], '');
    }
    if ($time[0] < 0) {
        return remaining_time_bet_array_values(
            'rates__item rates__item--end',
            'timer timer--end',
            'Торги окончены', '');
    }
    if ($time[0] <= 24) {
        return remaining_time_bet_array_values(
            'rates__item',
            'timer timer--finishing',
            $time[0] . ':' . $time[1], '');
    }
}

/**
 * Возвращает победителя лота, меняет значение поля 'closed' из таблицы 'lot'  БД
 *
 * @param $user_bet_lot_id - ид лота
 * @param $user_bet_user_id - ид пользователя
 * @param $expiration_time - время до истечения срока партии
 *
 * @return $winner - возвращает 1 - есль пользователь победитель и 0 если нет
 */
function winner_user($user_bet_lot_id, $user_bet_user_id, $expiration_time)
{
    global $db_connection;
    $winner = 0;
    if (strtotime($expiration_time) < strtotime('now')) {
        $sql_last_bet_id = "SELECT user_id, id as max_id FROM bid WHERE lot_id = " . $user_bet_lot_id . " ORDER BY id DESC LIMIT 1";
        $sql_last_bet_id_query = mysqli_query($db_connection, $sql_last_bet_id);
        $last_bet_id = mysqli_fetch_array($sql_last_bet_id_query, MYSQLI_ASSOC);
        if ($last_bet_id['user_id'] === $user_bet_user_id) {
            $winner = 1;
        }
    }
    return $winner;
}

/**
 * Возвращает время ввода ставки соответствующему формату
 *
 * @param $bid_time - время введенной ставки
 *
 * @return false|string - возвращает время соответствующему формату
 */
function bid_time($bid_time)
{
    $bid_time_strtotime = strtotime('now') - strtotime($bid_time);
    $bid_time_minutes = ceil($bid_time_strtotime / 60);
    $bid_time_hours = floor($bid_time_strtotime / 3600);
    if ($bid_time_hours === 0 && ($bid_time_minutes >= 0 || ($bid_time_minutes < 60))) {
        return $bid_time_minutes . ' ' . get_noun_plural_form($bid_time_minutes, 'минута назад', 'минуты назад',
                'минут назад');
    }
    if ($bid_time_hours === 1) {
        return 'час назад';
    }
    if ($bid_time_hours > 1 && $bid_time_hours < 12) {
        return $bid_time_hours . ' ' . get_noun_plural_form($bid_time_hours, 'час назад', 'часа назад', 'часов назад');
    }
    return date_format(date_create($bid_time), 'Y-m-d в H:i');
}
?>