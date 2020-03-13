<?php

namespace App\Front\Components;


use App\Model\Orm\Orm;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
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
		$grid->addColumnNumber('id', "$cDomain.id");
		$grid->addColumnNumber('text1', "$cDomain.text");
		$grid->addColumnDateTime('createdAt', "$cDomain.created");

		return $grid;
	}

}

interface IDatagridTestControlFactory
{
	public function create($id = NULL): DatagridTestControl;
}