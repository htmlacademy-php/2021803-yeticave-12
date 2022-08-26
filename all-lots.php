<?php

/**
 * @var array $lot
 * @var array $categories
 * @var string $user_name
 * @var bool $is_auth
 * @var mysqli $link
 * @var string $lots_category
 */

require_once 'helpers.php';
require_once 'data.php';
require_once 'functions.php';
require_once 'init.php';

$lots_category = filter_input(INPUT_GET, 'category');
if (!$lots_category) {
    header("Location:/404.php");
    die();
}
$categories = get_categories($link);
$lot = get_lot_category($link, $lots_category);
$category = get_categories_symbol_code($link, $lots_category);
$page_content = include_template('all-lots.php', ['lot' => $lot, 'categories' => $categories, 'lots_category' => $lots_category]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $category[0]['name'],
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);
print($layout_content);
