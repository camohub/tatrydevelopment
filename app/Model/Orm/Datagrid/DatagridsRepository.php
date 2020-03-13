<?php

namespace App\Model\Orm\Datagrids;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


class DatagridsRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Datagrid::class];
	}
}
