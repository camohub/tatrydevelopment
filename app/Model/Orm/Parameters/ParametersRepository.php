<?php

namespace App\Model\Orm;


use Nextras\Orm\Repository\Repository;


class ParametersRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Parameter::class];
	}
}
