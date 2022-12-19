<?
/**
 * @var array $lot
 * @var array $categories
 * @var string $user_name
 * @var mysqli $link
 */

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);
$user_name = check_session_name();
const LOT_LIMIT = 9; //Кол-во лотов на странице

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || empty(trim($_GET['search']))) { //Проверяем, что была отправлена форма и получившаяся строка не пустая.

    $search = '';

    $page_content = include_template('search.php', [
        'categories' => $categories,
        'search' => $search,

    ]);

    $layout_content = include_template('layout.php', [
        'categories' => $categories,
        'content' => $page_content,
        'title' => 'Страница поиска',
        'user_name' => $user_name,
    ]);

    print($layout_content);
    exit();
}

$currentPage = (int)($_GET['page'] ?? 1);

$offset = LOT_LIMIT * ($currentPage - 1); 

$search = trim(filter_input(INPUT_GET, 'search'));
$search = mysqli_real_escape_string($link, $search);

if (empty($search_result = get_lot_search($link, $search, LOT_LIMIT, $offset))) {
    $search = 'Ничего не найдено';
}

$count_lot_search = get_count_lot_search($link, $search); //Получаем кол-во найденных лотов

$pagination = create_pagination($currentPage, $count_lot_search, LOT_LIMIT); //Создаем пагинацию

$page_content = include_template('search.php', [
    'categories' => $categories,
    'lots' => $lots,
    'search' => $search,
    'search_result'=> $search_result,
    'pagination' => $pagination,
]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $page_content,
    'title' => 'Страница поиска',
    'user_name' => $user_name,
]);

print($layout_content);