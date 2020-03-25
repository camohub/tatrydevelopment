<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Front\Components\DatagridTestControl;
use App\Front\Components\DatagridUsersControl;
use App\Front\Components\IDatagridTestControlFactory;
use App\Front\Components\IDatagridUsersControlFactory;
use App\Front\Components\ITestControlFactory;
use App\Front\Components\TestControl;
use App\Model\Orm\Orm;
use App\Presenters\BasePresenter;
use Nette\Database\Context;
use Tracy\Debugger;


class DefaultPresenter extends BasePresenter
{

	/** @var  ITestControlFactory @inject */
	public $testControlFactory;

	/** @var  IDatagridTestControlFactory @inject */
	public $datagridTestControlFactory;

	/** @var  IDatagridUsersControlFactory @inject */
	public $datagridUsersControlFactory;


	public function actionDefault()
	{
		$this->template->test = 'Before ajax call';

		/*$user = $this->orm->users->getById(2);
		$this->orm->users->removeAndFlush($user);*/

		if( $this->isAjax() )
		{
			$this->template->test = 'After ajax call ' . date('h:i:s');
			$this->redrawControl('test');
		}
	}


	public function renderTest()
	{
		$products = $this->orm->products->findAll();

		foreach ($products as $p)
		{
			Debugger::barDump($p->name, 'name');
		}
		//$this->user->logout();
	}


	public function actionSignIn()
	{
		$this['modal']->addContent($this['signInForm']);
		$this->showModal = !$this->user->isLoggedIn();
		$this->setView('default');
	}







	protected function createComponentTest(): TestControl
	{
		return $this->testControlFactory->create('aaaaaaaaaaaaaaaaaa');
	}


	protected function createComponentDatagridTest(): DatagridTestControl
	{
		return $this->datagridTestControlFactory->create('ddddddddddddddddddd');
	}


	protected function createComponentDatagridUsers(): DatagridUsersControl
	{
		return $this->datagridUsersControlFactory->create('ddddddddddddddddddd');
	}
}
