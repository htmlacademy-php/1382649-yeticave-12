<?php
require_once('helpers.php');
$categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
$announces = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 10999,
        'url' => 'img/lot-1.jpg',
        'expiration_date' => "2020-06-15"
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'url' => 'img/lot-2.jpg',
        'expiration_date' => "2020-06-16"
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'url' => 'img/lot-3.jpg',
        'expiration_date' => "2020-06-19"
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'price' => 10999,
        'url' => 'img/lot-4.jpg',
        'expiration_date' => "2020-06-20"
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => 7500,
        'url' => 'img/lot-5.jpg',
        'expiration_date' => "2020-06-15"
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'url' => 'img/lot-6.jpg',
        'expiration_date' => "2020-06-21"
    ]
];
$user_name = 'Nicoleta';

$main = include_template('main.php', ['categories' => $categories, 'announces' => $announces]);
$content = include_template('layout.php', ['content' => $main, 'user_name' => $user_name, 'title' => 'Главная', 'categories' => $categories]);
print ($content);
?>
