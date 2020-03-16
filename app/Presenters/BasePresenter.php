<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Front\Components\IModalControlFactory;
use App\Front\Components\ISignInFormControlFactory;
use App\Front\Components\ModalControl;
use App\Front\Components\SignInFormControl;
use Nette;
use Tracy\Debugger;


class BasePresenter extends Nette\Application\UI\Presenter
{

	/** @var string @persistent */
	public $locale = 'sk';

	/** @var \Kdyby\Translation\Translator @inject */
	public $translator;

	/** @var IModalControlFactory @inject */
	public $modalControlFactory;

	/** @var  ISignInFormControlFactory @inject */
	public $signInFormControlFactory;

	/** @var boolean */
	public $showModal = FALSE;


	public function handleSetLocale($l)
	{
		$this->translator->setLocale($l);
	}


	public function beforeRender()
	{
		parent::beforeRender();
		$this->redrawControl('flash');

		$this->template->showModal = $this->showModal;
		$this->template->locales = [];
		foreach ($this->translator->getAvailableLocales() as $l)
		{
			$this->template->locales[] = strtolower(explode('_', $l)[0]);
		}
	}


	protected function createComponentModal(): ModalControl
	{
		return $this->modalControlFactory->create();
	}


	protected function createComponentSignInForm(): SignInFormControl
	{
		return $this->signInFormControlFactory->create();
	}


	public function flashSuccess($msg)
	{
		$this->flashMessage($msg, 'success');
	}

	public function flashDanger($msg)
	{
		$this->flashMessage($msg, 'error');
	}



	/**
	 * @param string $id
	 * @return string|void
	 */
	public function setReferer( $id = '' )
	{
		if ( ! $id ) return '';

		$referer = $this->getHttpRequest()->getReferer();
		$current = $this->getHttpRequest()->getUrl();

		// Ak sa opakuje napr. zobrazenie formulára s chybou tak je
		// adresa referer a current rovnaká a nechceme ju ukladať.
		if( ! $referer ) return;
		if( $referer && $current && $current->getPath() == $referer->getPath() ) return;


		$url = $referer->getScheme() . '://' . $referer->getHost() . $referer->getPath();
		$qs = '';

		if ( $qsArr = $referer->getQueryParameters() ) // returns array
		{
			foreach ( $qsArr as $key => $val )
			{
				if ( $key == self::FLASH_KEY ) continue;

				$url .= !$qs ? '&' . $key . '=' . $val : '?' . $key . '=' . $val;
			}
		}

		$this->getSession( $id )->url = $url . $qs;
	}


	/**
	 * @desc Returns referrer url or false
	 * @param string $id
	 * @return bool|mixed|string
	 */
	public function getReferer( $id = '' )
	{
		$sess = $this->getSession( $id );

		if ( ! $id || ! $url = $sess->url ) return FALSE;

		$url = $sess->url;
		$url .= ( parse_url( $url, PHP_URL_QUERY ) ? '&' : '?' );
		$url .= self::FLASH_KEY . '=' . $this->getParameter( self::FLASH_KEY );
		Debugger::barDump($url);

		unset( $sess->url );

		return null;
		return $url;
	}
}
