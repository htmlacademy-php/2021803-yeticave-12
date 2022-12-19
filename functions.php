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
    return htmlspecialchars($_POST[$value] ?? "");
}

//Проверка заполненности 
function validate_filled($value): ?string
{
    if (empty($value)) {
        return "Поле необходимо заполнить";
    }
    return null;
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
    $bid_step = intval($bid_step);
    if ($bid_step <= 0) {
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

//Поиск пользователей по email
function get_user_by_email(mysqli $link, string $email): array
{
    $sql = "SELECT * FROM user
    WHERE email='$email'";
    return get_query_sql_results($link, mysqli_query($link, $sql));
}

//Проверка email при регистрации
function validate_email_signup(mysqli $link, string $email): ?string
{
    if ($email === '') {
        return "Поле необходимо заполнить";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Некорректно введен e-mail";
    }
    if (get_user_by_email($link, $email)) {
        return "E-mail используется другим пользователем";
    }

    return null;
}

//Проверка полей формы регистрации
function validate_signup_form(mysqli $link, array $signup_form): array
{
    $errors = [
        'email' => validate_email_signup($link, $signup_form['email']),
        'password' => validate_filled($signup_form['password']),
        'name' => validate_filled($signup_form['name']),
        'contacts' => validate_filled($signup_form['contacts'])
    ];

    return array_filter($errors);
}

//Добавление нового пользователя
function add_user(mysqli $link, array $signup_form): bool
{
    $signup_form['password'] = password_hash($signup_form['password'], PASSWORD_DEFAULT);

    $sql = 'INSERT INTO user(email, password, name, contacts) VALUES (?, ?, ?, ?)';

    $stmt = db_get_prepare_stmt($link, $sql, $signup_form);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        return true;
    } else {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }
}

//Аутентификация
function authentication(mysqli $link, array $login_form): bool
{
    $user = get_user_by_email($link, $login_form['email']);
    if ($user === null) {
        return false;
    }
    $_SESSION['user_id'] = $user[0]['id'];
    $_SESSION['name'] = $user[0]['name'];

    return true;
}

//Получение id пользователя по сессии
function get_user_id_session(): ?string
{
    return $_SESSION['user_id'] ?? null;
}

//Проверка на открытие сессии (наличие name в массиве SESSION)
function check_session_name(): ?string
{
    return $_SESSION['name'] ?? null;
}

//Проверка email при входе
function validate_email_login(string $email): ?string
{
    if ($email === '') {
        return "Поле необходимо заполнить";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Некорректно введен e-mail";
    }
    return null;
}

//Проверка пароля при входе
function validate_password(mysqli $link, string $email, string $password): ?string
{
    if ($password === '') {
        return "Поле необходимо заполнить";
    }
    $user = get_user_by_email($link, $email);
    if ($user === null) {
        return "Неверный e-mail или пароль";
    }
   if(!password_verify($password,$user['0']['password'])){
        return "Введен неверный пароль";
   }

    return null;
}

//Валидация формы входа
function validate_login_form(mysqli $link, array $login_form): array
{
    $errors = [
        'email' => validate_email_login($login_form['email']),
        'password' => validate_password($link, $login_form['email'], $login_form['password'])
    ];

    return array_filter($errors);
}

//Поиск лотов
function get_lot_search(mysqli $link, string $search, int $limit, int $offset): array {
    $sql = "SELECT l.id, l.name AS lot_name, l.description, l.initial_price, l.img_url, l.finished_date, c.name AS cat_name
            FROM lot l
            JOIN category c ON l.category_id = c.id
            WHERE  MATCH(l.name, l.description) AGAINST(? IN BOOLEAN MODE) ORDER BY l.created_date LIMIT ".$limit." OFFSET ".$offset."";

    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

//
function get_count_lot_search(mysqli $link, string $search): int {

    $sql = 'SELECT l.id, l.name AS lot_name, l.description, l.initial_price, l.img_url, l.finished_date, c.name AS cat_name
            FROM lot l
            JOIN category c ON l.category_id = c.id
            WHERE  MATCH(l.name, l.description) AGAINST(? IN BOOLEAN MODE)';

    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return count(mysqli_fetch_all($result, MYSQLI_ASSOC));
}


function create_pagination(int $current, int $countLot, int $limit): array {
  $countPage = (int)ceil($countLot/$limit); //Получаем кол-во страниц
  $pages = range(1, $countPage); //Создаём массив страниц

  $prev = ($current > 1) ? $current - 1 : $current;
  $next = ($current < $countPage) ? $current + 1 : $current;

  return ['prevPage' => $prev,
          'nextPage' => $next,
          'countPage' => $countPage,
          'pages' => $pages,
          'currentPage' => $current,
          'lotLimit' => $limit
         ];
}