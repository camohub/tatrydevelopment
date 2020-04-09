<?php

namespace App\Model\Orm;


use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\ManyHasOne;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * @property int                                  $id               {primary}
 * @property string                               $lang
 * @property string                               $value
 *
 * @property ManyHasOne|Product|NULL              $product          {m:1 Product::$parameters}
 * @property ManyHasOne|Parameter|NULL            $parameter        {m:1 Parameter::$productsParameters}
 */
class ProductParameter extends Entity
{

}