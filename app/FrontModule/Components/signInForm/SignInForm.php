<?php

namespace App\Front\Components;


use App\Exceptions\AccessDeniedException;
use App\Model\Orm\Orm;
use App\Model\UserManager;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nextras\Orm\InvalidArgumentException;
use Tracy\Debugger;


class SignInFormControl extends Control
{

	public $userManager;

	public $translator;

	public $orm;

	public $modalTitle = '//forms.signInForm.title';


	public function __construct(Orm $orm , UserManager $u, Translator $t)
	{
		$this->orm = $orm;
		$this->userManager = $u;
		$this->translator = $t;
	}


	public function render()
	{
		$this->getPresenter()->setReferer(self::class);
		$this->template->setFile(__DIR__ . '/signInForm.latte');
		$this->template->render();
	}


	public function createComponentForm( string $name ): Form
	{
		$form = new Form();
		$form->setTranslator( $this->translator->domain('forms.signInForm'));

		$form->addText('name', 'name.label')
			->setRequired('name.required');

		$form->addPassword('password', 'password.label')
			->setRequired('password.required')
			->addRule($form::MIN_LENGTH, 'password.length', 6);

		$form->addPassword('password2', 'password2.label')
			->setRequired('password2.required')
			->addRule($form::EQUAL, 'password2.equal', $form['password']);

		$form->addSubmit('sbmt');

		$form->onSuccess[] = [$this, 'formSucceeded'];

		return $form;
	}


	public function formSucceeded(Form $form, $values)
	{
		$presenter = $this->getPresenter();
		try
		{
			$presenter->user->login($values->name, $values->password);
		}
		catch( AuthenticationException $e )
		{
			if( $e->getCode() == UserManager::IDENTITY_NOT_FOUND ) $form->addError('userNotFound');
			elseif ( $e->getCode() == UserManager::INVALID_CREDENTIAL ) $form->addError('invalidCredentials');
			return;
		}
		catch( AccessDeniedException $e )
		{
			$form->addError('userInactive');
			return;
		}
		catch( \Exception $e )
		{
			$form->addError('//forms.unexpectedError');
			return;
		}


		if( $form->hasErrors() )  // Only for sure
		{
			$form->addError('//forms.unexpectedError');
			return;
		}

		$presenter->flashSuccess('//forms.signInForm.success');
		$referer = $presenter->getReferer(self::class);
		$referer ? $presenter->redirectUrl($referer) : $presenter->redirect('this');
	}

}

interface ISignInFormControlFactory
{
	public function create(): SignInFormControl;
}