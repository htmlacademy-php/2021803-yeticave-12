<?php

/**
 * @var array $lot
 * @var array $categories
 * @var string $user_name
 * @var mysqli $link
 */

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php';

$lot_id = filter_input(INPUT_GET, 'id');
if (!$lot_id) {
    header("Location:/404.php");
    die();
}
$user_id = get_user_id_session();
$categories = get_categories($link);
$lot = get_lot_id($link, $lot_id);
if (!$lot) {
    header("Location:/404.php");
    die();
}

$page_content = include_template('lot.php', ['lot' => $lot, 'categories' => $categories,'user_id' => $user_id,]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $lot[0]['name'],
]);
print($layout_content);
