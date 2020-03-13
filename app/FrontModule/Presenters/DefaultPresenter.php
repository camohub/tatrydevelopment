<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Front\Components\DatagridTestControl;
use App\Front\Components\IDatagridTestControlFactory;
use App\Front\Components\ISignInFormControlFactory;
use App\Front\Components\ITestControlFactory;
use App\Front\Components\SignInFormControl;
use App\Front\Components\TestControl;
use App\Model\Orm\Orm;
use App\Presenters\BasePresenter;
use Nette\Database\Context;


class DefaultPresenter extends BasePresenter
{

	/** @var  Context @inject */
	public $database;

	/** @var  ITestControlFactory @inject */
	public $testControlFactory;

	/** @var  IDatagridTestControlFactory @inject */
	public $datagridTestControlFactory;

	/** @var  ISignInFormControlFactory @inject */
	public $signInFormControlFactory;

	/** @var  Orm @inject */
	public $orm;

	public function actionDefault()
	{
		//$user = $this->orm->users->getBy(['name =' => 'Čamo', 'password !=' => NULL]);
		//Debugger::barDump($user, 'Presenter dump');
		/*Debugger::barDump($this->translator->getAvailableLocales());
		Debugger::barDump($this->translator->getDefaultLocale());
		Debugger::barDump($this->translator->getLocale());*/
	}

	public function actionEshop()
	{
	}

	public function actionSelenium()
	{

	}

	public function actionExample()
	{

	}


	protected function createComponentTest(): TestControl
	{
		return $this->testControlFactory->create('aaaaaaaaaaaaaaaaaaaaaaa');
	}


	protected function createComponentDatagridTest(): DatagridTestControl
	{
		return $this->datagridTestControlFactory->create('ddddddddddddddddddd');
	}


	protected function createComponentSignInForm(): SignInFormControl
	{
		return $this->signInFormControlFactory->create();
	}
}
