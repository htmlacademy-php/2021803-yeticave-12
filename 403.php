<?php

/**
 * @var array $categories
 * @var mysqli $link
 */

require_once 'helpers.php';
require_once 'functions.php';
require_once 'init.php';

$categories = get_categories($link);

header("HTTP/1.1 403 Forbidden");

$page_content = include_template('403.php', ['categories' => $categories]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Доступ запрещён',
]);

print($layout_content);
