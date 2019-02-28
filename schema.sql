
CREATE DATABASE IF NOT EXISTS doings_done
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;


USE doings_done;


CREATE TABLE IF NOT EXISTS user (
       user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
       name VARCHAR(50) NOT NULL,
       email VARCHAR(128) NOT NULL,
       pass VARCHAR(255) NOT NULL,
       date_reg TIMESTAMP NOT NULL,
       PRIMARY KEY (user_id),
       UNIQUE KEY (email)
);


CREATE TABLE IF NOT EXISTS project (
       project_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
       project_name VARCHAR(50) NOT NULL,

       user_author INT UNSIGNED NOT NULL,
       PRIMARY KEY (project_id),
       FOREIGN KEY (user_author) REFERENCES user(user_id)
);


CREATE TABLE IF NOT EXISTS task (
       task_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
       date_create TIMESTAMP NOT NULL,
       date_done TIMESTAMP,
       flag_done BOOL DEFAULT 0 NOT NULL,
       task_name VARCHAR(128) NOT NULL,
       file VARCHAR(255),
       date_completion TIMESTAMP,

       user_author INT UNSIGNED NOT NULL,
       project_id INT UNSIGNED NOT NULL,
       PRIMARY KEY (task_id),
       FOREIGN KEY (user_author) REFERENCES user(user_id),
       FOREIGN KEY (project_id) REFERENCES project(project_id)
);


CREATE UNIQUE INDEX iu_user$user_id ON user(user_id);
CREATE UNIQUE INDEX iu_project$project_name$user_author ON project(project_name, user_author);
