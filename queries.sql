USE yeticave;

# Добавление категорий
INSERT INTO categories (name)
VALUES ('Доски и лыжи'),('Крепления'),('Ботинки'),('Одежда'),('Инструменты'),('Разное');


# Добавление пользователей
INSERT INTO users (email, name, password)
VALUES
  ('ignat.v@gmail.com','Игнат','$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka'),
  ('kitty_93@li.ru','Леночка','$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa'),
  ('warrior07@mail.ru','Руслан','$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW');


# Добавление лотов
INSERT INTO lots (name, description, img_path, price, expiration, price_step, category_id, author_id)
VALUES
  ('2014 Rossignol District Snowboard', 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами. Стекловолокно Bi-Ax,  уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
   'lot-1.jpg', 10999, '2018-03-10', 100, (SELECT id FROM categories WHERE name = 'Доски и лыжи'), 1),
  ('DC Ply Mens 2016/2017 Snowboard', 'описание лота', 'lot-2.jpg', 159999, '2018-03-10', 1000, (SELECT id FROM categories WHERE name = 'Доски и лыжи'), 1),
  ('Крепления Union Contact Pro 2015 года размер L/XL', 'описание лота', 'lot-3.jpg', 8000, '2018-03-10', 500, (SELECT id FROM categories WHERE name = 'Крепления'), 2),
  ('Ботинки для сноуборда DC Mutiny Charocal', 'описание лота', 'lot-4.jpg', 109999, '2018-03-10', 1000, (SELECT id FROM categories WHERE name = 'Ботинки'), 2),
  ('Куртка для сноуборда DC Mutiny Charocal', 'описание лота', 'lot-5.jpg', 7500, '2018-03-10', 200, (SELECT id FROM categories WHERE name = 'Одежда'), 3),
  ('Маска Oakley Canopy', 'описание лота', 'lot-6.jpg', 5400, '2018-03-10', 250, (SELECT id FROM categories WHERE name = 'Разное'), 3);


# Добавление ставок
INSERT INTO bets (sum, user_id, lot_id)
VALUES
  ( 11100, 1, (SELECT id FROM lots WHERE name = '2014 Rossignol District Snowboard')),
  ( 11500, 2, (SELECT id FROM lots WHERE name = '2014 Rossignol District Snowboard'));


# получить все категории
SELECT name
FROM categories;


# получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену(?), количество ставок, название категории
SELECT lots.name, lots.price, lots.img_path,
  #количество ставок
  COUNT(bets.lot_id) as bets_count,
  #максимальная ставка
  MAX(bets.sum) as max_bet,
  #текущая цена
  GREATEST( COALESCE(MAX(bets.sum), 0) , lots.price) as current_price,
  cat.name
FROM lots
  LEFT OUTER JOIN bets
    ON lots.id = bets.lot_id
  JOIN categories cat
    ON lots.category_id = cat.id
WHERE lots.expiration > NOW()
GROUP BY lots.id;


# показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT lots.name, cat.name
FROM lots
  JOIN categories cat
    ON lots.category_id = cat.id
WHERE lots.id = 1;


# обновить название лота по его идентификатору
UPDATE lots SET name = 'Маска Oakley Canopy'
WHERE id = 6;


# получить список самых свежих ставок для лота по его идентификатору
SELECT sum
FROM bets
WHERE lot_id = 1
ORDER BY dt_add DESC;