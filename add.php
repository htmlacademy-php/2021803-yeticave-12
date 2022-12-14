<?php

/**
 * @var array $lot
 * @var array $categories
 * @var mysqli $link
 * @var array $errors
 */

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php';

$categories = get_categories($link);
$errors = [];

$user_id = get_user_id_session();
if ($user_id === null) {
    header("Location: /403.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = filter_input_array(INPUT_POST, [
        'name' => FILTER_DEFAULT, 'category_id' => FILTER_DEFAULT, 'finished_date' => FILTER_DEFAULT,
        'description' => FILTER_DEFAULT, 'img_url' => FILTER_DEFAULT, 'initial_price' => FILTER_DEFAULT,  'bid_step' => FILTER_DEFAULT
    ], true);

    $categories_id = array_column($categories, 'id');
    $errors = validate_form_add_lot($lot, $categories_id, $_FILES);
    $errors = array_filter($errors);
    if (empty($errors)) {
        $result = add_lot($link, $lot, $_FILES);
        if ($result) {
            $lot_id = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lot_id);
        } else {
            header("Location:/404.php");
        }
    }
}
$page_content = include_template('add.php', ['categories' => $categories, 'errors' => $errors]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Добавление лота',
]);

debug_to_console($errors);
print($layout_content);
