<?php

namespace App\Model\Orm;

use App\Model\Orm\Datagrids\DatagridsRepository;
use App\Model\Orm\Users\CategoriesRepository;
use App\Model\Orm\Users\UsersRepository;
use App\Model\Orm\Roles\RolesRepository;
use Nextras\Orm\Model\Model;
use App\Model\Orm\Products\ProductsRepository;


/**
 * Model
 * @property UsersRepository $users
 * @property RolesRepository $roles
 * @property DatagridsRepository $datagrids
 * @property CategoriesRepository $categories
 */
class Orm extends Model
{

}