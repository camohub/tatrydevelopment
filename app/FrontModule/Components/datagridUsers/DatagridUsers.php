<?php

namespace App\Front\Components;


use App\Model\Orm\Orm;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Nextras\Orm\Mapper\Dbal\DbalCollection;
use Tracy\Debugger;
use Ublaboo\DataGrid\Column\Action\Confirmation\StringConfirmation;
use Ublaboo\DataGrid\DataGrid;


class DatagridUsersControl extends Control
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
		$this->template->setFile(__DIR__ . '/datagridUsers.latte');
		$this->template->render();
	}


	public function createComponentGrid( string $name ): DataGrid
	{
		$cDomain = 'components.datagridUsers';
		$grid = new DataGrid($this, 'grid');
		$grid->setTranslator($this->translator);
		$grid->setDataSource($this->orm->users->findAll());
		$grid->setDefaultPerPage(20);
		$grid::$iconPrefix = 'glyphicon glyphicon-';

		$this->setId($grid, $cDomain);
		$this->setName($grid, $cDomain);
		$this->setEmail($grid, $cDomain);
		$this->setRoles($grid, $cDomain);
		$this->setActive($grid, $cDomain);
		$this->setActive2($grid, $cDomain);
		$this->setCreated($grid, $cDomain);
		$this->setActionDelete($grid, $cDomain);
		$this->setSum($grid, $cDomain);

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


	protected function setRoles(DataGrid $grid, $cDomain)
	{
		$grid->addColumnText('roles', "$cDomain.roles")
			->setTemplateEscaping(FALSE)
			->setRenderer(function ($item) {
				$rNames = [];
				foreach ($item->roles as $r) $rNames[] = $r->name;
				return join(', ', $rNames);
			})
			->setFilterText('roles')
			->setCondition(function ($collection, $value) {
				/** @var DbalCollection $collection */
				$collection->getQueryBuilder()
					->innerJoin('users', 'users_x_roles' , 'uxr', 'users.id = uxr.user_id')
					->innerJoin('uxr', 'roles' , 'roles', 'uxr.role_id = roles.id')
					->andWhere('roles.name LIKE %s', "%$value%");
			});
	}


	protected function setActive(DataGrid $grid, $cDomain)
	{
		$grid->addColumnText('active', "$cDomain.active")
			->setTemplateEscaping(FALSE)
			->setRenderer(function ($item) use ($cDomain) {
				$class = $item->active ? 'text-success' : 'text-danger';
				$text = $this->translator->translate($cDomain . '.active' . (int)$item->active);
				return '<span class="' . $class . '">' . $text . '</span>';
			})
			->setSortable()
			->setFilterSelect([
				2 => $this->translator->translate("$cDomain.activeAll"),
				0 => $this->translator->translate("$cDomain.active1"),
				1 => $this->translator->translate("$cDomain.active1")
			], 'active')
			->setCondition(function ($collection, $value) {
				// TODO: filter throws an error
				if( (int)$value != 2 ) $collection->getQueryBuilder()->andWhere('active = %i', (int)$value);
			});
	}


	protected function setActive2(DataGrid $grid, $cDomain)
	{
		$grid->addColumnStatus('active2', "$cDomain.active", 'active')
			->addOption(true, "$cDomain.active")
			->setClass('btn-success')
			->endOption()
			->addOption(false, "$cDomain.inactive")
			->setClass('btn-danger')
			->endOption()
			->onChange[] = [$this, 'activeChange'];
	}


	public function activeChange($id, $newValue)
	{
		$user = $this->orm->users->getById($id);
		$user->active = (bool)$newValue;
		$this->orm->users->persistAndFlush($user);

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


	protected function setSum(DataGrid $grid, $cDomain)
	{
		$grid->setColumnsSummary(['id']);
	}


	protected function setActionDelete(DataGrid $grid, $cDomain)
	{
		$grid->addAction('deleteUser!', "$cDomain.delete")
			->setIcon('trash')
			->setConfirmation(
				new StringConfirmation("$cDomain.confirmDelete", 'name') // Second parameter is optional
			);
	}


	public function handleDeleteUser($id)
	{
		if( $user = $this->orm->users->getById($id) )
		{
			$user->deleted = 'now';
			$this->orm->users->persistAndFlush($user);
		}
	}
}

interface IDatagridUsersControlFactory
{
	public function create($id = NULL): DatagridUsersControl;
}