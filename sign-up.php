<?php

/**
 * @var mysqli $link
 * @var array $errors
 */

require_once 'helpers.php';
require_once 'data.php';
require_once 'functions.php';
require_once 'init.php';

$categories = get_categories($link);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $signup_form = filter_input_array(INPUT_POST, [
        'email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT, 'name' => FILTER_DEFAULT,
        'contacts' => FILTER_DEFAULT
    ], true);

    $errors = validate_signup_form($link, $signup_form);
    if (empty($errors)) {
        add_user($link, $signup_form);
        header("Location: /login.php");
        die();
    }
}
$content = include_template('sign-up.php', ['categories' => $categories, 'errors' => $errors]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'Регистрация'
]);

print($layout_content);
