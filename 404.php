<?php

/**
 * @var array $categories
 * @var string $title
 * @var string $user_name
 * @var bool $is_auth
 * @var mysqli $link
 */

require_once 'helpers.php';
require_once 'data.php';
require_once 'functions.php';
require_once 'init.php';

$categories = get_categories($link);

$page_content = include_template('404.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Страница не найдена',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);
print($layout_content);