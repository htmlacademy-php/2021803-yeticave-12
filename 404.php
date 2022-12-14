<?php

/**
 * @var array $categories
 * @var string $title
 * @var mysqli $link
 */

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php';

$categories = get_categories($link);

$page_content = include_template('404.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Страница не найдена',
]);
print($layout_content);