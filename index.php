<?php

/**
 * @var array $lots
 * @var array $categories
 * @var mysqli $link
 */

require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

$user_name = check_session_name();
$lots = get_lots($link);
$categories = get_categories($link);

$page_content = include_template('main.php', ['lots' => $lots, 'categories' => $categories]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'user_name' => $user_name,
    'title' => 'Главная',
]);

print($layout_content);