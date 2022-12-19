USE yeticave;

/*список категорий */
INSERT INTO category (name , symbol_code) VALUES 
('Доски и лыжи', 'boards'),
('Крепления', 'attachment'),
('Ботинки','boots'),
('Одежда','clothing'),
('Инструменты','tools'),
('Разное','other');

/* список пользователей*/
INSERT INTO user (created_date,email,name,password,contacts) VALUES
('2022-07-26','user1@gmail.com','Елена','$2a$12$cM0UF6WgFSDMatLriPjJWuO0X11dvwZi8mMxVQ.KeBhP5L65yAnSG','88311234561'),
('2022-07-27','user2@gmail.com','Сергей','password2','88311234562'),
('2022-08-01','user3@gmail.com','Андрей','password3','88311234563');

/*список объявлений*/
INSERT INTO lot (user_id,winner_id,category_id,created_date,finished_date,name,description,img_url,initial_price,bid_step) VALUES
('1','1','1','2022-12-17','2022-12-28','2014 Rossignol District Snowboard','описание','img/lot-1.jpg','10999','100'),
('2','2','1','2022-12-18','2022-12-30','DC Ply Mens 2016/2017 Snowboard','описание','img/lot-2.jpg','159999','100'),
('1','1','2','2022-12-18','2023-01-02','Крепления Union Contact Pro 2015 года размер L/XL','описание','img/lot-3.jpg','8000','100'),
('2','2','3','2022-12-18','2022-12-27','Ботинки для сноуборда DC Mutiny Charocal','описание','img/lot-4.jpg','10999','100'),
('3','3','4','2022-12-19','2023-01-22','Куртка для сноуборда DC Mutiny Charocal','описание','img/lot-5.jpg','7500','100'),
('3','3','6','2022-12-19','2022-12-29','Маска Oakley Canopy','описание','img/lot-6.jpg','5400','100');

/*ставки*/
INSERT INTO bid (user_id,lot_id,created_date,price) VALUES
('1','2','2022-12-19','160499'),
('3','2','2022-12-19','160699');

/*Получить все категории*/
SELECT * FROM category;

/*получить самые новые, открытые лоты.
Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории*/
SELECT l.name,l.initial_price,l.img_url,MAX(b.price),c.name 
FROM lot l 
LEFT JOIN bid b ON l.id=b.lot_id
JOIN category c ON c.id=l.category_id
WHERE l.finished_date > CURDATE()
GROUP BY l.id ORDER by l.created_date DESC;

/*показать лот по его ID.
Получите также название категории, к которой принадлежит лот*/
SELECT l.*, c.name FROM lot l 
JOIN category c ON c.id=l.category_id
WHERE l.id = 6;

/*обновить название лота по его идентификатору*/
UPDATE lot SET name = 'Жилет для сноуборда DC Mutiny Charocal' WHERE id = 5;

/*получить список ставок для лота по его идентификатору с сортировкой по дате*/
SELECT * FROM bid b
JOIN lot l ON b.lot_id=l.id 
WHERE l.id = 2 ORDER BY b.created_date ASC;