<?php

namespace App\Model\Orm;

use Nextras\Orm\Entity\Entity;


/**
 * @property int                    $id          {primary}
 * @property Category|NULL          $category    {m:1 Category::$langs}
 * @property string                 $lang
 * @property string                 $name
 * @property string                 $slug
 */
class CategoryLang extends Entity
{

}