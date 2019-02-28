
USE doings_done;


INSERT INTO user (name, email, pass, date_reg)
       VALUES ('admin', 'admin@admin.com', '123', CURRENT_TIMESTAMP);


INSERT INTO project (project_name, user_author)
       VALUES ('Входящие', 1);

INSERT INTO project (project_name, user_author)
       VALUES ('Учеба', 1);

INSERT INTO project (project_name, user_author)
       VALUES ('Работа', 1);

INSERT INTO project (project_name, user_author)
       VALUES ('Домашние дела', 1);

INSERT INTO project (project_name, user_author)
       VALUES ('Авто', 1);


INSERT INTO task (date_create, date_done, flag_done, task_name, user_author, project_id)
       VALUES ('2019-01-24 00:00:00', '2019-02-24 00:00:00', FALSE, 'Собеседование в IT компании', 1, 3);

INSERT INTO task (date_create, date_done, flag_done, task_name, user_author, project_id)
       VALUES ('2019-01-21 00:00:00', '2019-12-25 00:00:00', FALSE, 'Выполнить тестовое задание', 1, 3);

INSERT INTO task (date_create, date_done, flag_done, task_name, user_author, project_id)
       VALUES ('2019-02-10 00:00:00', '2019-12-21 00:00:00', TRUE, 'Сделать задание первого раздела', 1, 2);

INSERT INTO task (date_create, date_done, flag_done, task_name, user_author, project_id)
       VALUES ('2019-02-05 00:00:00', '2019-12-22 00:00:00', FALSE, 'Встреча с другом', 1, 1);

INSERT INTO task (date_create, date_done, flag_done, task_name, user_author, project_id)
       VALUES ('2019-02-01 00:00:00', NULL, FALSE, 'Купить корм для кота', 1, 4);

INSERT INTO task (date_create, date_done, flag_done, task_name, user_author, project_id)
       VALUES ('2019-01-20 00:00:00', NULL, FALSE, 'Заказать пиццу', 1, 4);


SELECT project_name
  FROM project
 WHERE user_author = 1;

SELECT task_name
  FROM task
 WHERE project_id = 3;

UPDATE task
   SET flag_done = TRUE
 WHERE task_id = 1;

UPDATE task
   SET task_name = 'Собеседование в Яндекс'
 WHERE task_id = 1;
