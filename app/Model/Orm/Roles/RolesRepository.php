<?php

namespace App\Model\Orm;


use Nextras\Orm\Repository\Repository;


class RolesRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Role::class];
	}
}
