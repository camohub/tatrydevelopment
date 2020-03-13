<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class InitMigration extends AbstractMigration
{
	/**
	* Migrate Up.
	*/
	public function up()
	{
		$this->query('SET FOREIGN_KEY_CHECKS=0');
		$this->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
		$this->query('SET AUTOCOMMIT = 0');
		$this->query('START TRANSACTION');
		$this->query('SET time_zone = "+00:00"');

		$this->query('
			CREATE TABLE IF NOT EXISTS `datagrids` (
				`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`text1` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
				`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;
			 
			INSERT INTO `datagrids` (`id`, `text1`, `created_at`) VALUES
				(1, \'aaaaaaaaaaaaaaaaa\', \'2020-03-12 09:04:19\'),
				(2, \'bbbbbbbbbbbbbbbbbbbbbbbbbb\', \'2020-03-12 09:04:19\'),
				(3, \'ccccccccccccccccccccc\', \'2020-03-12 09:04:19\'),
				(4, \'ddddddddddddddddddddddd\', \'2020-03-12 09:04:19\'),
				(5, \'eeeeeeeeeeeeeee\', \'2020-03-12 09:04:19\'),
				(6, \'fffffffffffffffffff\', \'2020-03-12 09:04:19\'),
				(7, \'hhhhhhhhhhhhhhhhh\', \'2020-03-12 09:04:19\'),
				(8, \'iiiiiiiiiiiiiiii\', \'2020-03-12 09:04:19\'),
				(9, \'jjjjjjjjjjjjjjjjj\', \'2020-03-12 09:04:19\'),
				(10, \'kkkkkkkkkkkkkkkkkkk\', \'2020-03-12 09:04:19\'),
				(11, \'lllllllllllllllllll\', \'2020-03-12 09:04:19\'),
				(12, \'mmmmmmmmmmmmmmmmmmmmmmm\', \'2020-03-12 09:04:19\'),
				(13, \'nnnnnnnnnnnnnnnnnnn\', \'2020-03-12 09:04:19\'),
				(14, \'oooooooooooooooooooo\', \'2020-03-12 09:04:19\'),
				(15, \'pppppppppppppppppppppp\', \'2020-03-12 09:04:19\'),
				(16, \'rrrrrrrrrrrrrrrr\', \'2020-03-12 09:04:19\'),
				(17, \'sssssssssssssssssssssss\', \'2020-03-12 09:04:19\'),
				(18, \'ttttttttttttttttttttt\', \'2020-03-12 09:04:19\'),
				(19, \'uuuuuuuuuuuuuuuuu\', \'2020-03-12 09:04:19\'),
				(20, \'vvvvvvvvvvvvvvvvvvvvvv\', \'2020-03-12 09:04:19\'),
				(21, \'xxxxxxxxxxxxxxxxxxxxxxxx\', \'2020-03-12 09:04:19\'),
				(22, \'zzzzzzzzzzzzzzzzzzzzzzzz\', \'2020-03-12 09:04:19\'),
				(23, \'yyyyyyyyyyyyyyyyyyyyyy\', \'2020-03-12 09:04:19\'),
				(24, \'qqqqqqqqqqqqqqqqqqqqq\', \'2020-03-12 09:04:19\'),
				(25, \'wwwwwwwwwwwwwwwwwwwwww\', \'2020-03-12 09:04:19\'),
				(26, \'1111111111111111111\', \'2020-03-12 09:04:19\'),
				(27, \'22222222222222\', \'2020-03-12 09:04:19\'),
				(28, \'333333333333333333333\', \'2020-03-12 09:04:19\'),
				(29, \'444444444444444444\', \'2020-03-12 09:04:19\'),
				(30, \'55555555555555555555\', \'2020-03-12 09:04:19\'),
				(31, \'666666666666666666666666\', \'2020-03-12 09:04:19\'),
				(32, \'77777777777777777777\', \'2020-03-12 09:04:19\'),
				(33, \'888888888888888888\', \'2020-03-12 09:04:19\'),
				(34, \'99999999999999999999999\', \'2020-03-12 09:04:19\'),
				(35, \'11111111111111111111111111111111111111111\', \'2020-03-12 09:04:19\'),
				(36, \'aaaaaaaaaaaaaaaaa\', \'2020-03-12 09:05:21\'),
				(37, \'bbbbbbbbbbbbbbbbbbbbbbbbbb\', \'2020-03-12 09:05:21\'),
				(38, \'ccccccccccccccccccccc\', \'2020-03-12 09:05:21\'),
				(39, \'ddddddddddddddddddddddd\', \'2020-03-12 09:05:21\'),
				(40, \'eeeeeeeeeeeeeee\', \'2020-03-12 09:05:21\'),
				(41, \'fffffffffffffffffff\', \'2020-03-12 09:05:21\'),
				(42, \'hhhhhhhhhhhhhhhhh\', \'2020-03-12 09:05:21\'),
				(43, \'iiiiiiiiiiiiiiii\', \'2020-03-12 09:05:21\'),
				(44, \'jjjjjjjjjjjjjjjjj\', \'2020-03-12 09:05:21\'),
				(45, \'kkkkkkkkkkkkkkkkkkk\', \'2020-03-12 09:05:21\'),
				(46, \'lllllllllllllllllll\', \'2020-03-12 09:05:21\'),
				(47, \'mmmmmmmmmmmmmmmmmmmmmmm\', \'2020-03-12 09:05:21\'),
				(48, \'nnnnnnnnnnnnnnnnnnn\', \'2020-03-12 09:05:21\'),
				(49, \'oooooooooooooooooooo\', \'2020-03-12 09:05:21\'),
				(50, \'pppppppppppppppppppppp\', \'2020-03-12 09:05:21\'),
				(51, \'rrrrrrrrrrrrrrrr\', \'2020-03-12 09:05:21\'),
				(52, \'sssssssssssssssssssssss\', \'2020-03-12 09:05:21\'),
				(53, \'ttttttttttttttttttttt\', \'2020-03-12 09:05:21\'),
				(54, \'uuuuuuuuuuuuuuuuu\', \'2020-03-12 09:05:21\'),
				(55, \'vvvvvvvvvvvvvvvvvvvvvv\', \'2020-03-12 09:05:21\'),
				(56, \'xxxxxxxxxxxxxxxxxxxxxxxx\', \'2020-03-12 09:05:21\'),
				(57, \'zzzzzzzzzzzzzzzzzzzzzzzz\', \'2020-03-12 09:05:21\'),
				(58, \'yyyyyyyyyyyyyyyyyyyyyy\', \'2020-03-12 09:05:21\'),
				(59, \'qqqqqqqqqqqqqqqqqqqqq\', \'2020-03-12 09:05:21\'),
				(60, \'wwwwwwwwwwwwwwwwwwwwww\', \'2020-03-12 09:05:21\'),
				(61, \'1111111111111111111\', \'2020-03-12 09:05:21\'),
				(62, \'22222222222222\', \'2020-03-12 09:05:21\'),
				(63, \'333333333333333333333\', \'2020-03-12 09:05:21\'),
				(64, \'444444444444444444\', \'2020-03-12 09:05:21\'),
				(65, \'55555555555555555555\', \'2020-03-12 09:05:21\'),
				(66, \'666666666666666666666666\', \'2020-03-12 09:05:21\'),
				(67, \'77777777777777777777\', \'2020-03-12 09:05:21\'),
				(68, \'888888888888888888\', \'2020-03-12 09:05:21\'),
				(69, \'99999999999999999999999\', \'2020-03-12 09:05:21\'),
				(70, \'11111111111111111111111111111111111111111\', \'2020-03-12 09:05:21\')
		');

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
				PRIMARY KEY (`id`),
				UNIQUE KEY `users_name_uk` (`name`),
				UNIQUE KEY `users_email_uk` (`email`)
			) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci
		');

		$this->query('
			INSERT INTO `users` (`id`, `name`, `email`, `password`, `active`, `confirmation_code`, `social_network_params`, `resource`, `created`) VALUES
			(1, \'Čamo\', \'vladimir.camaj@gmail.com\', \'$2y$10$1/aTxIK.UnCYg9EO5EvPiOyLVxoLe8Vuw91PS1LgIPlEIk3i4mNjq\', 1, NULL, NULL, \'App\', \'2020-03-12 16:19:07\');
		');

		$this->query('
			CREATE TABLE IF NOT EXISTS `users_x_roles` (
				`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`user_id` int(10) UNSIGNED NOT NULL,
				`role_id` int(10) UNSIGNED NOT NULL,
				PRIMARY KEY (`id`),
				KEY `users_x_roles_user_id_k` (`user_id`),
				KEY `users_x_roles_role_id_k` (`role_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci
		');

		$this->query('INSERT INTO `users_x_roles` (`user_id`, `role_id`) VALUES (1, 10)');
		$this->query('SET FOREIGN_KEY_CHECKS=1');
		$this->query('COMMIT');
	}


	/**
	* Migrate Down.
	*/
	public function down()
	{

	}

}