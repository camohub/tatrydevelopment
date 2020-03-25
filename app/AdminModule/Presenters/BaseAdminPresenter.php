<?php

declare(strict_types=1);


namespace App\AdminModule\Presenters;


use App\Front\Components\IModalControlFactory;
use App\Front\Components\ISignInFormControlFactory;
use App\Front\Components\ModalControl;
use App\Front\Components\SignInFormControl;
use App\Presenters\BasePresenter;
use Nette;
use Tracy\Debugger;


class BaseAdminPresenter extends BasePresenter
{

	public function startup()
	{
		parent::startup(); // TODO: Change the autogenerated stub

		if( !$this->user->isLoggedIn() ) $this->redirect(':Front:Default:signIn');
		$this->checkPermission('administration', 'view', 'Nemáte oprávnenie pre vstup do administrácie.', ':Front:Default:default');
	}


	public function checkPermission($source, $action, $msg, $redirect = ':Admin:Default:default')
	{
		if( !$this->user->isAllowed($source, $action) )
		{
			$this->flashDanger($msg);
			$this->redirect($redirect);
		}
	}
}