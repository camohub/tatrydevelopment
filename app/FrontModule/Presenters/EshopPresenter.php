<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Front\Components\DatagridTestControl;
use App\Front\Components\DatagridUsersControl;
use App\Front\Components\EshopCategoriesControl;
use App\Front\Components\IDatagridTestControlFactory;
use App\Front\Components\IDatagridUsersControlFactory;
use App\Front\Components\IEshopCategoriesControlFactory;
use App\Front\Components\ITestControlFactory;
use App\Front\Components\ModalControl;
use App\Front\Components\TestControl;
use App\Model\Orm\Orm;
use App\Model\Services\CategoriesService;
use App\Presenters\BasePresenter;
use Nette\Database\Context;
use Tracy\Debugger;


class EshopPresenter extends BasePresenter
{

	/** @var IEshopCategoriesControlFactory @inject */
	public $eshopCategoriesFactory;

	/** @var CategoriesService @inject */
	public $categoriesService;


	public function actionDefault($id = '')
	{
		$category = $this->orm->categories->getBy(['this->langs->name' => $id]);
		$products = $this->categoriesService->findCategoryProducts($category);

		$this->template->products = $products;

		if( $this->isAjax() )
		{

		}
	}


	public function createComponentEshopCategories(): EshopCategoriesControl
	{
		return $this->eshopCategoriesFactory->create();
	}

}
