<?php

namespace App\Model\Orm\Users;


use Nextras\Orm\Repository\Repository;


class CategoriesRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Category::class];
	}
}
