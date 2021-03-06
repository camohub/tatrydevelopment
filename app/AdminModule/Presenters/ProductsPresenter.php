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

	/** @var  App\Admin\Forms\IProductCreateFormControlFactory @inject */
	public $productsCreateFormFactory;

	/** @var  App\Admin\Forms\IParameterCreateFormControlFactory @inject */
	public $parameterCreateFormFactory;

	public $id = NULL;


	public function startup()
	{
		parent::startup();
		$this->checkPermission('product', 'edit', 'Nemáte oprávnenie editovať produkty.');
	}


	public function actionDefault()
	{

	}


	public function actionCreate( $id = NULL, $createParameter = FALSE)
	{
		$this->id = $id;

		if($createParameter)
		{
			$this->showModal = TRUE;
			$this['modal']->addContent($this['createParameterForm']);
		}
	}


/////////////////////////////////////////////////////////////////////////////////////////
////// Controls ////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////


	public function createComponentProductsDatagrid()
	{
		return $this->productsDatagridFactory->create();
	}

	public function createComponentCreateForm()
	{
		return $this->productsCreateFormFactory->create($this->id);
	}

	public function createComponentCreateParameterForm()
	{
		return $this->parameterCreateFormFactory->create();
	}

}
