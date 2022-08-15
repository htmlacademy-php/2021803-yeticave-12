<?php

//Функция форматирования цены
function price_format($price)
{
    return number_format(ceil($price), 0, '', ' ') . ' ₽';
}

//Функция перевода оставшегося времени в формат «ЧЧ: ММ»
function remaining_time(string $closeTime): array
{
    $dt_diff = strtotime($closeTime) - strtotime(date('Y-m-d'));
    if (!is_date_valid($closeTime) || $dt_diff < 0) {
        return [0, 0];
    }
    $hours = floor($dt_diff / 3600);
    $minutes = floor($dt_diff % 3600 / 60);
    return [$hours, $minutes];
}

//Получение результата SQL-запроса
function get_query_sql_results(mysqli $link, $result): array
{
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        print("Error MySQL: " . mysqli_error($link));
        return [];
    }
}

//Получение списка новых лотов
function get_lots(mysqli $link)
{
    $sql = "SELECT l.*,c.name as cat_name 
FROM lot l
JOIN category c ON c.id=l.category_id
WHERE l.finished_date > CURDATE()
ORDER by l.created_date DESC LIMIT 6";
    return get_query_sql_results($link, mysqli_query($link, $sql));
}

//Получение списка категорий
function get_categories(mysqli $link)
{
    $sql = "SELECT * FROM category";
    return  get_query_sql_results($link, mysqli_query($link, $sql));
}