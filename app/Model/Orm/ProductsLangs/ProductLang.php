<?php

namespace App\Model\Orm;


use Nextras\Orm\Entity\Entity;


/**
 * @property int                        $id        {primary}
 * @property Product|NULL               $product   {m:1 Product::$langs}
 * @property string                     $lang
 * @property string                     $name
 * @property string                     $desc
 */
class ProductLang extends Entity
{

}