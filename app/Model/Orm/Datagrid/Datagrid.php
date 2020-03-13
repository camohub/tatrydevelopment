<?php

namespace App\Model\Orm\Datagrids;

use DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * Post
 *
 * @property int                        $id          {primary}
 * @property string                     $text1
 * @property DateTimeImmutable          $createdAt   {default now}
 */
class Datagrid extends Entity
{

}