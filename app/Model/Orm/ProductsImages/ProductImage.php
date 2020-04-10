<?php

namespace App\Model\Orm;

use App\Presenters\BasePresenter;
use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Mapper\Dbal\DbalCollection;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;
use Tracy\Debugger;


/**
 * @property int                  $id                {primary}
 * @property Product|NULL         $product           {m:1 Product::$images}
 * @property string               $file
 */
class ProductImage extends Entity
{

}