USE yeticave;

-- Добавление данных в таблицу category
INSERT INTO category (name)
VALUES ('Доски и лыжи'),
       ('Крепления'),
       ('Ботинки'),
       ('Одежда'),
       ('Инструменты'),
       ('Разное');

-- Добавление данных в таблицу lot (обьявленья)
INSERT INTO lot (name, category_id, init_price, final_date)
VALUES ('2014 Rossignol District Snowboard', 1, 10999, '2020-06-15'),
       ('DC Ply Mens 2016/2017 Snowboard', 1, 159999, '2020-06-16'),
       ('Крепления Union Contact Pro 2015 года размер L/XL', 2, 8000, '2020-06-19'),
       ('Ботинки для сноуборда DC Mutiny Charocal', 3, 10999, '2020-06-20'),
       ('Куртка для сноуборда DC Mutiny Charocal', 4, 7500, '2020-06-1'),
       ('Маска Oakley Canopy', 6, 5400, '2020-06-21');

-- Добавление данных в таблицу lot_img (изображения обьявлениях)
INSERT INTO lot_img (image_url, lot_id)
VALUES ('img/lot-1.jpg', 1),
       ('img/lot-2.jpg', 2),
       ('img/lot-3.jpg', 3),
       ('img/lot-4.jpg', 4),
       ('img/lot-5.jpg', 5),
       ('img/lot-6.jpg', 6);

-- Добавление данных в таблицу user
INSERT INTO user (name, email, password)
VALUES ('User1', 'user1@mail.ru', '123456'),
       ('User2', 'user2@gmail.com', 'asdfgh');

-- Добавление данных в таблицу bid (ставки пользователей)
INSERT INTO bid (user_id, lot_id, bid_value)
VALUES (1, 1, 11500),
       (2, 3, 8500),
       (1, 5, 7600);

-- Выборка всех данных с таблицы category
SELECT *
FROM category;

/*Выборка самых новых, открытых лотов. Включает название, стартовую цену, ссылку на изображение,
  текущую цену, название категории */
SELECT lot.name, init_price, image_url, bid_value, category.name
FROM lot
         LEFT JOIN lot_img ON lot.id = lot_img.lot_id
         LEFT JOIN bid ON bid.lot_id = lot.id
         LEFT JOIN category ON category.id = lot.category_id
WHERE lot.final_date > NOW()
ORDER BY lot.id DESC
LIMIT 3;

/* Показать лот по его id + название категории, к которой принадлежит лот;*/
SELECT lot.id, lot.name, category.name as category, description, lot_img.image_url init_price, step, final_date
FROM lot
         LEFT JOIN category ON category.id = lot.category_id
         LEFT JOIN lot_img ON lot_img.lot_id = lot.id
WHERE lot.id = 1;

-- Обновление название лота по его идентификатору
UPDATE lot
SET name = "NewName"
WHERE id = 1;

-- Cписок ставок для лота по его идентификатору с сортировкой по дате.
SELECT lot.name, user.name, bid_value, bid_time
FROM bid
         LEFT JOIN lot ON lot.id = bid.lot_id
         LEFT JOIN user ON user.id = bid.user_id
WHERE lot.id = 1
ORDER BY bid_time DESC;

