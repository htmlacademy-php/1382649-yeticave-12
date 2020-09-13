<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function formatting_prices($price)
{
    $price = ceil($price);

    if ($price >= 1000) {
        $price = number_format($price, 0, ',', ' ');
    }

    $amount = $price . ' <img src="img\rub.svg">';

    return $amount;
}

function get_dt_range($expiration_date)
{
    $dt_now_timestamp = strtotime("now");
    $dt_expiration_timestamp = strtotime($expiration_date);
    $remaining_time_timestamp = $dt_expiration_timestamp - $dt_now_timestamp;
    $remaining_hours = floor($remaining_time_timestamp / 3600);
    $remaining_minutes = floor(($remaining_time_timestamp % 3600) / 60);

    return [$remaining_hours, $remaining_minutes];
}

function remaining_time($expiration_date)
{
    $remaining_time = get_dt_range($expiration_date);
    if ($remaining_time[0] >= '1') {
        echo '<div class="lot__timer timer">';
        echo $remaining_time[0] . ':' . $remaining_time[1];
        echo '</div>';
    } else {
        echo '<div class="timer--finishing timer">';
        echo $remaining_time[0] . ':' . $remaining_time[1];
        echo '</div>';
    }
}

function return404()
{
    header('Status: 404', TRUE, 404);
    include __DIR__ . '/404.php';
    die();
}

function getPostVal($name)
{
    return $_POST[$name] == null ? "" : $_POST[$name];
}

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

function isCorrectLength($name, $min, $max)
{
    $len = strlen($_POST[$name]);

    if ($len < $min or $len > $max) {
        return "Значение должно быть до $max символов";
    }
    return null;
}

function validateIssetCategory($categories, $category)
{
    if (!in_array($category, $categories)) {
        return "Выберите категорию";
    }
    return null;
}

function validateImage($name)
{
    if ($_FILES[$name]['size'] == 0) {
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

function validatePrice()
{
    if (intval($_POST['lot-rate']) <= 0) {
        return "Введите начальную цену";
    }
    return null;
}

function validateStep()
{
    if (intval($_POST['lot-step']) <= 0) {
        return "Введите шаг ставки";
    }
    return null;
}

function convertDataToTimestamp()
{
    $post_date = $_POST['lot-date'];
    return $timestamp_post_date = strtotime($post_date);
}

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

function validateEmail($email, $db_connection)
{
    if (empty($email)) {
        return "Введите адрес электронной почты";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Вы ввели неправильный адрес электронной почты';
    }

    $sql_compare_email = "SELECT email FROM user WHERE email ='" . $email . "';";
    $sql_compare_email_query = mysqli_query($db_connection, $sql_compare_email);
    $sql_email_comparation_result = mysqli_fetch_array($sql_compare_email_query, MYSQLI_NUM);
    if ($sql_email_comparation_result != NULL) {
        return 'Такой адрес электронной почты уже существует';
    }

    return null;
}

function validatePassword($password)
{
    if (strlen($password) == 0) {
        return "Введите пароль";
    } else if (strlen($password) < 8) {
        return "Пароль должен иметь минимум 8 символов";
    }

}

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

function verifyEmail($email, $db_connection)
{
    if (empty($email)) {
        return "Введите адрес электронной почты";
    }
    return null;
}

function logout()
{
    session_start();
    $_SESSION = [];
}
?>
