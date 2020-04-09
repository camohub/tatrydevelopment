<?php


namespace App\AdminModule\Presenters;


use App;
use App\Admin\Components\IAdminEshopMenuControlFactory;
use App\AdminModule\Forms\CategoriesEditFormFactory;
use App\AdminModule\Forms\CategoryFormFactory;
use Tracy\Debugger;


class ParametersPresenter extends BaseAdminPresenter
{
	/** @var  App\Admin\Components\IDatagridProductsControlFactory @inject */
	public $productsDatagridFactory;

	/** @var  App\Admin\Forms\IParameterCreateFormControlFactory @inject */
	public $parameterCreateFormFactory;

	public $id = NULL;


	public function startup()
	{
		parent::startup();
		$this->checkPermission('product', 'edit', 'Nemáte oprávnenie editovať parametre.');
	}


	public function actionDefault()
	{

	}


	public function actionCreate($id = NULL)
	{

	}


///////////// ///////////////////////////////////////////////////////////////////////////
////// Controls ////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////

	public function createComponentCreateForm()
	{
		return $this->parameterCreateFormFactory->create();
	}

}
