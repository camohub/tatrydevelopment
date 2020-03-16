<?php

namespace App\Model\Orm\Users;

use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * @property int                    $id          {primary}
 * @property Category               $parent      {m:1 Category::$categories}
 * @property OneHasMany|Category[]  $categories  {1:m Category::$parent}
 */
class Category extends Entity
{

}