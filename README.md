# PMS - Persoanl Management System

Technologies used: Yii2.0.9 with Gii extension, Active Form, Active Record, Ajax
Using DBMS: MySQL, Yii Migration, Data Transaction

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Install Database

CREATE TABLE `cities` (
`id` bigint(20) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
`city` varchar(60) NOT NULL
);


CREATE TABLE `roles` (
`id` bigint(20) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
`role` varchar(60) NOT NULL default ''
);


CREATE TABLE `users` (
`id` bigint(20) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
`fullname` varchar(60) NOT NULL default '',
`email` varchar(100) NOT NULL default '',
`city_id` bigint(20) unsigned NOT NULL default '0'
);
ALTER TABLE `users` ADD INDEX ( `city_id` ) ;
ALTER TABLE `users` ADD FOREIGN KEY ( `city_id` ) REFERENCES `cities` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;


CREATE TABLE `projects` (
`id` bigint(20) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
`name` varchar(200) NOT NULL default '',
`description` longtext NOT NULL,
`status` varchar(60) NOT NULL default 'design'
);


CREATE TABLE `project_user` (
`id` bigint(20) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
`project_id` bigint(20) unsigned NOT NULL default '0',
`user_id` bigint(20) unsigned NOT NULL default '0',
`role_id` bigint(20) unsigned NOT NULL default '0'
);
ALTER TABLE `project_user` ADD INDEX ( `project_id` ) ;
ALTER TABLE `project_user` ADD INDEX ( `user_id` ) ;
ALTER TABLE `project_user` ADD INDEX ( `role_id` ) ;
ALTER TABLE `project_user` ADD FOREIGN KEY ( `project_id` ) REFERENCES `projects` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;
ALTER TABLE `project_user` ADD FOREIGN KEY ( `user_id` ) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;
ALTER TABLE `project_user` ADD FOREIGN KEY ( `role_id` ) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;


