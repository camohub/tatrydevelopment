<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Init extends AbstractMigration
{
	/**
	* Migrate Up.
	*/
	public function up()
	{
		$this->query('SET FOREIGN_KEY_CHECKS=0');
		$this->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
		$this->query('SET time_zone = "+00:00"');

		$this->query('
			CREATE TABLE IF NOT EXISTS `roles` (
			`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			`name` varchar(50) CHARACTER SET utf8 COLLATE utf8_slovak_ci NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci
		');

		$this->query(
			'INSERT INTO `roles` (`id`, `name`) VALUES
			(10, \'admin\'),
			(11, \'editor\'),
			(12, \'registered\')'
		);

		$this->query('
			CREATE TABLE IF NOT EXISTS `users` (
				`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`name` varchar(50) CHARACTER SET utf8 COLLATE utf8_slovak_ci NOT NULL,
				`email` varchar(50) CHARACTER SET utf8 COLLATE utf8_slovak_ci NOT NULL,
				`password` varchar(250) CHARACTER SET utf8 COLLATE utf8_slovak_ci DEFAULT NULL,
				`active` tinyint(3) UNSIGNED NOT NULL DEFAULT \'0\',
				`confirmation_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_slovak_ci DEFAULT NULL,
				`social_network_params` varchar(250) CHARACTER SET utf8 COLLATE utf8_slovak_ci DEFAULT NULL,
				`resource` varchar(50) CHARACTER SET utf8 COLLATE utf8_slovak_ci NOT NULL DEFAULT \'App\',
				`created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`deleted` datetime NULL DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `users_name_uk` (`name`),
				UNIQUE KEY `users_email_uk` (`email`)
			) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci
		');

		$this->query('
			INSERT INTO `users` (`id`, `name`, `email`, `password`, `active`, `confirmation_code`, `social_network_params`, `resource`, `created`) VALUES
			(1, \'Čamo\', \'vladimir.camaj@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(2, \'Čamo2\', \'vladimir.camaj2@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(3, \'Čamo3\', \'vladimir.camaj3@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(4, \'Čamo4\', \'vladimir.camaj4@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(5, \'Čamo5\', \'vladimir.camaj5@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(6, \'Čamo6\', \'vladimir.camaj6@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(7, \'Čamo7\', \'vladimir.camaj7@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(8, \'Čamo8\', \'vladimir.camaj8@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(9, \'Čamo9\', \'vladimir.camaj9@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(10, \'Čamo10\', \'vladimir.camaj10@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(11, \'Čamo11\', \'vladimir.camaj11@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(12, \'Čamo12\', \'vladimir.camaj12@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(13, \'Čamo13\', \'vladimir.camaj13@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(14, \'Čamo14\', \'vladimir.camaj14@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(21, \'Čamo21\', \'vladimir.camaj21@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(31, \'Čamo31\', \'vladimir.camaj31@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(41, \'Čamo41\', \'vladimir.camaj41@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(51, \'Čamo51\', \'vladimir.camaj51@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(61, \'Čamo61\', \'vladimir.camaj61@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(131, \'Čamo131\', \'vladimir.camaj131@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(121, \'Čamo121\', \'vladimir.camaj121@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(111, \'Čamo111\', \'vladimir.camaj111@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(101, \'Čamo101\', \'vladimir.camaj101@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(91, \'Čamo91\', \'vladimir.camaj91@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(81, \'Čamo81\', \'vladimir.camaj81@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\'),
			(71, \'Čamo71\', \'vladimir.camaj71@gmail.com\', \'$2y$10$HVq0g2G77hJX6JjvOIdk7OQGBCh3wOf7NTDecXz6n7hFeL5JaFK2W\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\');
		');

		$this->query('
			CREATE TABLE IF NOT EXISTS `users_x_roles` (
				`user_id` int(10) UNSIGNED NOT NULL,
				`role_id` int(10) UNSIGNED NOT NULL,
				PRIMARY KEY (`user_id`,`role_id`),
				KEY `users_x_roles_user_id_k` (`user_id`),
				KEY `users_x_roles_role_id_k` (`role_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci
		');

		$this->query('
			INSERT INTO `users_x_roles` (`user_id`, `role_id`) VALUES 
			(1, 10), (1, 11), (2, 11), (3, 11), (4, 11), (5, 11), (5, 12), 
			(6, 11), (13, 11), (12, 11), (11, 11), (10, 10), (9, 11), (9, 12), 
			(8, 11), (7, 11), (14, 10), (21, 11), (31, 11), (31, 12), (41, 10), 
			(51, 11), (51, 12), (61, 11), (131, 11), (121, 11), (121, 12), (111, 11), 
			(101, 11), (91, 11), (81, 11), (81, 12), (71, 11)
		');

		$this->query('SET FOREIGN_KEY_CHECKS=1');
	}


	/**
	* Migrate Down.
	*/
	public function down()
	{
		$this->query('
			DROP TABLE users_x_roles;
			DROP TABLE users;
			DROP TABLE roles;
			TRUNCATE TABLE phinxlog;
		');
	}

}