<?php

namespace App\Model\Orm\Users;


use Nextras\Orm\Repository\Repository;


class UsersRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [User::class];
	}
}
