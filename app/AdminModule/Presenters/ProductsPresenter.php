<?php


namespace App\AdminModule\Presenters;


use App;
use App\Admin\Components\IAdminEshopMenuControlFactory;
use App\AdminModule\Forms\CategoriesEditFormFactory;
use App\AdminModule\Forms\CategoryFormFactory;
use Tracy\Debugger;


class ProductsPresenter extends BaseAdminPresenter
{

	/** @var  App\Model\Services\CategoriesService @inject */
	public $categoriesService;

	/** @var  App\Admin\Components\IDatagridProductsControlFactory @inject */
	public $productsDatagridFactory;

	public $id = NULL;


	public function startup()
	{
		parent::startup();
		$this->checkPermission('product', 'edit', 'Nemáte oprávnenie editovať produkty.');
	}


	public function actionDefault()
	{

	}


///////////// ///////////////////////////////////////////////////////////////////////////
////// Controls ////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////


	public function createComponentProductsDatagrid()
	{
		return $this->productsDatagridFactory->create();
	}

}
