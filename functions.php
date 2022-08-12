<?php

//Функция форматирования цены
function price_format($price)
{
    return number_format(ceil($price), 0, '', ' ') . ' ₽';
}

//Функция перевода оставшегося времени в формат «ЧЧ: ММ»
function remaining_time(string $closeTime): array
{
    $dt_diff = strtotime($closeTime) - strtotime(date('H:i'));
    if (!is_date_valid($closeTime) || $dt_diff < 0) {
        return [0, 0];
    }
    $hours = floor($dt_diff / 3600);
    $minutes = floor($dt_diff % 3600 / 60);
    return [$hours, $minutes];
}