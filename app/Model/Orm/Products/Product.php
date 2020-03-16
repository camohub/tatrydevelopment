<?php

namespace App\Model\Orm\Products;

use DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * Post
 *
 * @property int                        $id          {primary}
 * @property string                     $title
 * @property string                     $content
 * @property DateTimeImmutable          $created   {default now}
 * @property DateTimeImmutable          $deleted   {default NULL}
 */
class Product extends Entity
{

}