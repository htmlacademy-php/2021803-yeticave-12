<?php

//Функция форматирования цены
function price_format($price)
{
    return number_format(ceil($price), 0, '', ' ') . ' ₽';
}

//Функция перевода оставшегося времени в формат «ЧЧ: ММ»
function remaining_time(string $closeTime): array
{
    $dt_diff = strtotime($closeTime) - strtotime(date('Y-m-d H:i'));
    if (!is_date_valid($closeTime) || $dt_diff < 0) {
        return [0, 0];
    }
    $hours = floor($dt_diff / 3600);
    $minutes = floor($dt_diff % 3600 / 60);
    return [$hours, $minutes];
}

//Получение результата SQL-запроса
function get_query_sql_results(mysqli $link, $result): array
{
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Error MySQL: " . mysqli_error($link));
        return [];
    }
}

//Получение списка новых лотов
function get_lots(mysqli $link)
{
    $sql = "SELECT l.*,c.name as cat_name 
FROM lot l
JOIN category c ON c.id=l.category_id
WHERE l.finished_date > CURDATE()
ORDER by l.created_date DESC LIMIT 6";
    return get_query_sql_results($link, mysqli_query($link, $sql));
}

//Получение списка категорий
function get_categories(mysqli $link)
{
    $sql = "SELECT * FROM category";
    return  get_query_sql_results($link, mysqli_query($link, $sql));
}

//Получение лота по его ID
function get_lot_id(mysqli $link, int $lot_id): array
{
    $sql = "SELECT l.*, c.name as cat_name,IFNULL(MAX(b.price),l.initial_price) as max_price FROM lot l
JOIN category c ON c.id=l.category_id
LEFT JOIN bid b ON l.id=b.lot_id
WHERE l.id= '" . $lot_id . "' 
GROUP BY l.id";
    return get_query_sql_results($link, mysqli_query($link, $sql));
}
//Получение списка лотов по категории
function get_lot_category(mysqli $link, string $lots_category): array
{
    $sql = "SELECT l.*,c.name as name_category,c.symbol_code FROM lot l
JOIN category c ON c.id=l.category_id
WHERE c.symbol_code= '$lots_category'";
    return get_query_sql_results($link, mysqli_query($link, $sql));
}

//Получение категории по коду
function get_categories_symbol_code(mysqli $link, string $lots_category): array
{
    $sql = "SELECT name FROM category
WHERE symbol_code='$lots_category'";
    return get_query_sql_results($link, mysqli_query($link, $sql));
}

//Получение значений из POST-запроса
function getPostVal($value): ?string
{
    return $_POST[$value] ?? "";
}

//Проверка заполненности 
function validate_filled($name)
{
    if (empty($_POST[$name])) {
        return "Это поле должно быть заполнено";
    }
}

//Проверка категории
function validate_category($id, $categories)
{
    if (!in_array($id, $categories)) {
        return "Указана несуществующая категория";
    }

    return null;
}

//Добавление лота
function add_lot(mysqli $link, array $lot, $files): bool
{
    $lot['finished_date'] = date("Y-m-d", strtotime($lot['finished_date']));
    $lot['img_url'] = upload_image($files);

    $sql = 'INSERT INTO lot (user_id,winner_id,name,category_id,created_date,finished_date,description,img_url,initial_price,bid_step) VALUES (3,3,?,?, NOW(),?,?,?,?,?)';
    $stmt = db_get_prepare_stmt($link, $sql, $lot);
    return mysqli_stmt_execute($stmt);
}

//Добавление картинки
function upload_image($file): string
{
    $temp_name = $file['img_url']['tmp_name'];
    $file_type = mime_content_type($temp_name);
    if ($file_type === 'image/png') {
        $file_name = uniqid() . '.png';
    } elseif ($file_type === 'image/jpeg') {
        $file_name = uniqid() . '.jpeg';
    } else {
        return '';
    }
    move_uploaded_file($temp_name, 'uploads/' . $file_name);
    return 'uploads/' . $file_name;
}

//Проверка формата картинки
function validate_img(array $files): string
{
    if (empty($files['img_url']['name'])) {
        return 'Загрузите картинку';
    }
    $temp_name = $files['img_url']['tmp_name'];
    $file_type = mime_content_type($temp_name);
    if ($file_type !== 'image/png' && $file_type !== 'image/jpeg') {
        return 'Загрузите картинку в формате .png или .jpeg';
    } else {
        return '';
    }
}

//Проверка цены
function validate_price(string $price): ?string
{
    if (intval($price) <= 0) {
        return "Значение должно быть больше нуля";
    }
    return null;
}

//Проверка даты
function validate_date(string $finished_date): string
{
    if (!is_date_valid($finished_date)) {
        return "Дата должна быть в формате «ГГГГ-ММ-ДД»";
    }
    $time = remaining_time($finished_date, 'now');
    if ($time[0] < 24) {
        return "Дата должна быть больше текущей даты, хотя бы на один день";
    }
    return '';
}

//Проверка шага ставки
function validate_bid_step(string $bid_step): ?string
{
    if (!ctype_digit($bid_step)) {
        return "Значение должно быть целым числом и больше нуля";
    }
    return null;
}

//Проверка полей
function validate_form_add_lot(array $lot, array $categories, $files): array
{

    $required_fields = ['name', 'category_id', 'description', 'img_url', 'initial_price', 'bid_step', 'finished_date'];
    $errors = [];

    $rules = [
        'category_id' => function ($category_id) use ($categories) {
            return validate_category($category_id, $categories);
        },
        'initial_price' => function ($initial_price) {
            return validate_price($initial_price);
        },
        'bid_step' => function ($bid_step) {
            return validate_bid_step($bid_step);
        },
        'finished_date' => function ($finished_date) {
            return validate_date($finished_date);
        }
    ];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    foreach ($lot as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }
    $errors['img_url'] = validate_img($files);
    return $errors;
}