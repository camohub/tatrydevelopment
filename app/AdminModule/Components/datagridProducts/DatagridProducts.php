<?php

namespace App\Admin\Components;


use App\Admin\Forms\ProductCreateFormControl;
use App\Model\Orm\Orm;
use App\Model\Orm\Product;
use App\Model\Services\ProductsService;
use App\Presenters\BasePresenter;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Nextras\Orm\Collection\Collection;
use Nextras\Orm\Mapper\Dbal\DbalCollection;
use Tracy\Debugger;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\DataGrid;


class DatagridProductsControl extends Control
{

	public $id;

	/** @var Translator  */
	public $translator;

	/** @var ProductsService  */
	public $productsService;

	/** @var Orm  */
	public $orm;


	public function __construct($id = NULL, Orm $orm , ProductsService $pS, Translator $t)
	{
		$this->translator = $t;
		$this->productsService = $pS;
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
		$grid->setDataSource($this->orm->products->findAdminSkProducts());
		$grid->setDefaultPerPage(20);
		$grid::$iconPrefix = 'fa fa-';

		$this->setId($grid, $cDomain);
		$this->setName($grid, $cDomain);
		$this->setPrice($grid, $cDomain);
		$this->setStock($grid, $cDomain);
		$this->setStatus($grid, $cDomain);
		$this->setCreated($grid, $cDomain);
		$this->setActionEdit($grid, $cDomain);
		$this->setActionDelete($grid, $cDomain);

		return $grid;
	}


	protected function setName(DataGrid $grid, $cDomain)
	{
		$grid->addColumnText('name', "$cDomain.name", "langs.name")
			->setTemplateEscaping(FALSE)
			->setRenderer(function ($item) {
				return '<b>' . $item->name . '</b>';
			})
			->setSortable()
			->setFilterText('name')
			->setCondition(function ($collection, $value) {
				/** @var DbalCollection $collection */
				$collection->getQueryBuilder()
					->innerJoin('products', 'products_langs', 'langs', 'products.id = langs.product_id')
					->andWhere('langs.name LIKE %s', "%$value%");
			});
	}


	protected function setPrice(DataGrid $grid, $cDomain)
	{
		$grid->addColumnText('price', "$cDomain.price");
	}


	protected function setStock(DataGrid $grid, $cDomain)
	{
		$grid->addColumnNumber('stock', "$cDomain.stock")
			->setSortable();
	}

	protected function setStatus(DataGrid $grid, $cDomain)
	{
		$grid->addColumnStatus('status', "$cDomain.statusActive")
			->addOption(1, "$cDomain.statusActive")
			->setClass('btn-success')
			->endOption()
			->addOption(2, "$cDomain.statusInactive")
			->setClass('btn-danger')
			->endOption()
			->onChange[] = [$this, 'statusChange'];

		$grid->addFilterSelect('status', 'status', [
				3 => $this->translator->translate("$cDomain.statusAll"),
				1 => $this->translator->translate("$cDomain.statusActive"),
				2 => $this->translator->translate("$cDomain.statusInactive"),
			], 'active')
			->setCondition(function ($collection, $value) {
				if( (int)$value != 3 ) $collection->getQueryBuilder()->andWhere('status = %i', (int)$value);
			});
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


	protected function setActionEdit(DataGrid $grid, $cDomain)
	{
		$grid->addAction(':Admin:Products:create', "$cDomain.edit")
			->setTitle("$cDomain.edit")
			->setIcon('edit');
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


	///////////////////////////////////////////////////////////////////////////////////////////////////
	//////// HANDLERS ////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////

	public function handleDeleteProduct($id)
	{
		$presenter = $this->getPresenter();

		if( $product = $this->orm->products->getById($id) )
		{
			$product->deleted = 'now';
			$product->status = Product::STATUS_DELETED;
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


	public function statusChange($id, $value)
	{
		$this->productsService->updateStatus($id, $value);

		if ($this->getPresenter()->isAjax()) {
			$this['grid']->redrawItem($id);
		}
	}
}




interface IDatagridProductsControlFactory
{
	public function create($id = NULL): DatagridProductsControl;
}