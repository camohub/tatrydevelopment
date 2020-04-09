<?php

namespace App\Admin\Components;


use App\Admin\Forms\ProductCreateFormControl;
use App\Model\Orm\Orm;
use App\Presenters\BasePresenter;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Nextras\Orm\Mapper\Dbal\DbalCollection;
use Tracy\Debugger;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\DataGrid;


class DatagridProductsControl extends Control
{

	public $id;

	public $translator;

	public $orm;


	public function __construct($id = NULL, Orm $orm , Translator $t)
	{
		$this->translator = $t;
		$this->orm = $orm;
		$this->id = $id;
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/datagridProducts.latte');
		$this->template->render();
	}


	public function createComponentGrid( string $name ): DataGrid
	{
		$cDomain = 'components.datagridProducts';
		$grid = new DataGrid($this, 'grid');
		$grid->setTranslator($this->translator);
		$grid->setDataSource($this->orm->products->findAdminProducts());
		$grid->setDefaultPerPage(20);
		$grid::$iconPrefix = 'fa fa-';

		$this->setId($grid, $cDomain);
		$this->setName($grid, $cDomain);
		$this->setActive($grid, $cDomain);
		$this->setCreated($grid, $cDomain);
		$this->setActionEdit($grid, $cDomain);
		$this->setActionDelete($grid, $cDomain);

		return $grid;
	}


	protected function setName(DataGrid $grid, $cDomain)
	{
		$grid->addColumnText('name', "$cDomain.name")
			->setTemplateEscaping(FALSE)
			->setRenderer(function ($item) {
				return '<b>' . $item->name . '</b>';
			})
			->setSortable()
			->setFilterText('name')
			->setCondition(function ($collection, $value) {
				$collection->getQueryBuilder()->andWhere('name LIKE %s', "%$value%");
			});
	}


	protected function setEmail(DataGrid $grid, $cDomain)
	{
		$grid->addColumnText('email', "$cDomain.name")
			/*->setTemplateEscaping(FALSE)
			->setRenderer(function ($item) {
				return '<b>' . $item->email . '</b>';
			})*/
			->setSortable()
			->setFilterText('email')
			->setCondition(function ($collection, $value) {
				$collection->getQueryBuilder()->andWhere('email LIKE %s', "%$value%");
			});
	}


	protected function setActive(DataGrid $grid, $cDomain)
	{
		$grid->addColumnStatus('active', "$cDomain.active", 'active')
			->addOption(true, "$cDomain.active")
			->setClass('btn-success')
			->endOption()
			->addOption(false, "$cDomain.inactive")
			->setClass('btn-danger')
			->endOption()
			->setFilterSelect([
				2 => $this->translator->translate("$cDomain.activeAll"),
				0 => $this->translator->translate("$cDomain.active0"),
				1 => $this->translator->translate("$cDomain.active1")
			], 'active')
			->setCondition(function ($collection, $value) {
				// TODO: filter throws an error??? Still alive????
				if( (int)$value != 2 ) $collection->getQueryBuilder()->andWhere('active = %i', (int)$value);
			})
			->onChange[] = [$this, 'activeChange'];
	}


	public function activeChange($id, $newValue)
	{
		$product = $this->orm->products->getById($id);
		$product->active = (bool)$newValue;
		$this->orm->products->persistAndFlush($product);

		if ($this->getPresenter()->isAjax()) {
			$this['grid']->redrawItem($id);
		}
	}


	protected function setCreated(DataGrid $grid, $cDomain)
	{
		$grid->addColumnDateTime('created', "$cDomain.created")
			->setFormat('j.n.Y H:i:s');
	}


	protected function setId(DataGrid $grid, $cDomain)
	{
		$grid->addColumnNumber('id', "$cDomain.id");
	}


	protected function setActionDelete(DataGrid $grid, $cDomain)
	{
		$grid->addAction('deleteProduct!', "")
			->setTitle("$cDomain.delete")
			->setIcon('trash')
			->setConfirmation(
				new StringConfirmation("$cDomain.confirmDelete", 'name') // Second parameter is optional
			);
	}


	protected function setActionEdit(DataGrid $grid, $cDomain)
	{
		$grid->addAction(':Admin:Products:create', "$cDomain.edit")
			->setTitle("$cDomain.edit")
			->setIcon('edit');
	}


	public function handleDeleteProduct($id)
	{
		$presenter = $this->getPresenter();

		if( $product = $this->orm->products->getById($id) )
		{
			$product->deleted = 'now';
			$this->orm->products->persistAndFlush($product);
		}

		$presenter->flashSuccess('//components.productsDatagrid.deleteSuccess');

		if ($presenter->isAjax())
		{
			$this['grid']->reload();
		}
		else
		{
			$presenter->redirect('this');
		}
	}
}

interface IDatagridProductsControlFactory
{
	public function create($id = NULL): DatagridProductsControl;
}