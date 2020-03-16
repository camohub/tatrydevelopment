<?php

namespace App\Front\Components;


use App\Model\Orm\Orm;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Tracy\Debugger;
use Ublaboo\DataGrid\DataGrid;


class DatagridTestControl extends Control
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
		$this->template->setFile(__DIR__ . '/datagridTest.latte');
		$this->template->render();
	}


	public function createComponentGrid( string $name ): DataGrid
	{
		$cDomain = 'components.datagrid';
		$grid = new DataGrid($this, 'grid');
		$grid->setTranslator($this->translator);
		$grid->setDataSource($this->orm->datagrids->findAll());

		$this->setId($grid, $cDomain);
		$this->setText1($grid, $cDomain);
		$this->setCreatedAt($grid, $cDomain);

		return $grid;
	}


	protected function setText1($grid, $cDomain)
	{
		$grid->addColumnNumber('text1', "$cDomain.text")
			->setTemplateEscaping(FALSE)
			->setRenderer(function ($item) {
				return '<b>' . $item->text1 . '</b>';
			})
			->setSortable()
			->setFilterText('text1')
			->setCondition(function ($collection, $value) {
				$collection->getQueryBuilder()->andWhere('text1 LIKE %s', "%$value%");
			});
	}


	protected function setCreatedAt($grid, $cDomain)
	{
		$grid->addColumnDateTime('createdAt', "$cDomain.created");
	}


	protected function setId($grid, $cDomain)
	{
		$grid->addColumnNumber('id', "$cDomain.id");
	}

}

interface IDatagridTestControlFactory
{
	public function create($id = NULL): DatagridTestControl;
}