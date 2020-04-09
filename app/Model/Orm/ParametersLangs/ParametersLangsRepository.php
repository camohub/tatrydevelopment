<?php

namespace App\Model\Orm;


use Nextras\Orm\Repository\Repository;


class ParametersLangsRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [ParameterLang::class];
	}
}
