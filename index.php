<?php

/**
 * @var array $products
 * @var array $categories
 * @var string $title
 * @var string $user_name
 * @var bool $is_auth
 */

require_once('helpers.php');
require_once('data.php');
require_once('functions.php');

$page_content = include_template('main.php', ['products' => $products, 'categories' => $categories]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => $title,
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);
print($layout_content);