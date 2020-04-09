<?php

namespace App\Model\Orm;


use Nextras\Orm\Repository\Repository;


class ProductsParametersRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [ProductParameter::class];
	}
}
