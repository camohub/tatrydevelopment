<?php

namespace App\Model\Orm;

use Nextras\Orm\Model\Model;
use App\Model\Orm\DatagridsRepository;
use App\Model\Orm\ProductsLangsRepository;
use App\Model\Orm\CategoriesRepository;
use App\Model\Orm\UsersRepository;
use App\Model\Orm\RolesRepository;
use App\Model\Orm\ProductsRepository;


/**
 * Model
 * @property UsersRepository $users
 * @property RolesRepository $roles
 * @property DatagridsRepository $datagrids
 * @property CategoriesRepository $categories
 * @property CategoriesLangsRepository $categoriesLangs
 * @property ProductsRepository $products
 * @property ProductsLangsRepository $productsLangs
 * @property ParametersRepository $parameters
 * @property ParametersLangsRepository $parametersLangs
 * @property ProductsParametersRepository $productsParameters
 * @property ProductsImagesRepository $productsImages
 */
class Orm extends Model
{

}