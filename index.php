<?php

/**
 * @var array $lots
 * @var array $categories
 * @var string $title
 * @var string $user_name
 * @var bool $is_auth
 * @var mysqli $link
 */

require_once('helpers.php');
require_once('data.php');
require_once('functions.php');
require_once('init.php');

$lots = get_lots($link);
$categories = get_categories($link);

$page_content = include_template('main.php', ['lots' => $lots, 'categories' => $categories]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);
print($layout_content);