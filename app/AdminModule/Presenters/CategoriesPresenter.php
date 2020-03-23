<?php


namespace App\AdminModule\Presenters;


use App;
use App\Admin\Components\IAdminEshopMenuControlFactory;
use Nette;
use App\AdminModule\Forms\CategoriesEditFormFactory;
use App\AdminModule\Forms\CategoryFormFactory;
use Tracy\Debugger;


class CategoriesPresenter extends BaseAdminPresenter
{

	/** @var  App\Model\Services\CategoriesService @inject */
	public $categoriesService;

	/** @var  App\Admin\Forms\ICategoryNameFormControlFactory @inject */
	public $categoryNameFormFactory;

	/** @var  IAdminEshopMenuControlFactory @inject */
	public $adminEshopCategoriesFactory;

	public $id = NULL;


	public function startup()
	{
		parent::startup();
		$this->checkPermission('category', 'edit', 'Nemáte oprávnenie editovať kategórie.');
	}


	public function actionDefault()
	{
		$categories = $this->orm->categories->findAll()->fetchAll();
		$this->template->categories = $categories;
	}


	public function actionCategories( $id = NULL )
	{
		$this->template->categories = $this->orm->categories->findAdminTopCategories();

		if( $id )
		{
			$this->id = (int)$id;
			$this->showModal = TRUE;
			$this['modal']->addContentComponent($this['editCategoryNameForm']);
			$this->setView('categories');

			if( $this->isAjax() )
			{
				$this->redrawControl('modal');
			}
		}
	}


	/**
	 * @secured
	 */
	public function handleCategoriesArticlesPriority()
	{
		try
		{
			$this->categoriesArticlesService->updatePriority( $_GET['categoryItems'] );
			$this->flashMessage( 'Poradie položiek bolo upravené.' );
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage() . ' @ in file ' . __FILE__ . ' on line ' . __LINE__, 'error' );
			$this->flashMessage( 'Pri ukladaní údajov došlo k chybe.', 'error' );
		}

		if ( $this->isAjax() )
		{
			$this->redrawControl( 'flash' );
			return;
		}

		$this->redirect( 'this' );

	}


	/**
	 * @param $id
	 * @throws App\Exceptions\AccessDeniedException
	 */
	public function handleChangeArticlesCategoryVisibility( $id )
	{
		$category = $this->categoryArticleRepository->find( $id );

		try
		{
			$this->categoriesArticlesService->switchVisibility( $category );
			// Dynamic snippet redraw needs to set only one item to template.
			$this->articlesCategories = $this->categoryArticleRepository->findBy( ['id' => $id] );
			$this->flashMessage( 'Viditeľnosť položky bola upravená.', 'success' );
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage(), 'error' );
			$this->flashMessage( 'Pri ukladaní údajov došlo k chybe.', 'error' );
		}

		if( $this->isAjax() )
		{
			$this->redrawControl( 'sortableList' );
			$this->redrawControl( 'sortableListScript' );
			$this->redrawControl( 'flash' );
			return;
		}

		$this->redirect( ':Admin:Menu:default' );

	}


	/**
	 * @param $id
	 * @throws App\Exceptions\AccessDeniedException
	 */
	public function handleDeleteArticleCategory( $id )
	{
		$category = $this->categoryArticleRepository->find( $id );

		try
		{
			$names = $result = $this->categoriesArticlesService->delete( $category );
			$this->articlesCategories = $this->categoryArticleRepository->findBy( ['parent_id' => NULL], ['priority' => 'ASC'] );
			$this->flashMessage( 'Category ' . join( ', ', $names ) . ' have been deleted.' );
		}
		catch ( App\Model\Services\NoArticleException $e )
		{
			return;
		}
		catch ( App\Model\Services\PartOfAppException $e )
		{
			$this->flashMessage( $e->getMessage(), 'error' );
			return;
		}
		catch ( App\Model\Services\ContainsArticleException $e )
		{
			$this->flashMessage( $e->getMessage(), 'error' );
			return;
		}
		catch ( \Exception $e )
		{
			Debugger::log( $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine() );
			$this->flashMessage( 'Pri ukladaní údajov došlo k chybe.', 'error' );
			return;
		}

		if ( $this->isAjax() )  // Not used for now.
		{
			$this->redrawControl( 'sortableList' );
			$this->redrawControl( 'flash' );
			return;
		}

		$this->redirect( ':Admin:Categories:articlesCategories' );

	}


////// Controls ////////////////////////////////////////////////////////////////////////

	public function createComponentAdminEshopCategories()
	{
		return $this->adminEshopCategoriesFactory->create();
	}


	public function createComponentEditCategoryNameForm()
	{
		return $this->categoryNameFormFactory->create( $this->id );
	}

}
