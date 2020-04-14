<?php


namespace App\Model\Services;


use App;
use App\Exceptions\CategoryContainsProductsException;
use App\Model\Entity;
use App\Model\Orm\Category;
use App\Model\Orm\CategoryLang;
use App\Model\Orm\Orm;
use App\Model\Orm\Product;
use Kdyby\Translation\Translator;
use Nette;
use Nette\Utils\Strings;


class CategoriesService
{

	const CACHE = self::class;

	const CACHE_TAG = self::class . 'tag';


	/** @var Orm */
	protected $orm;

	/** @var  @var Nette\Caching\IStorage */
	protected $storage;

	/** @var  @var Nette\Caching\Cache */
	public $cache;

	/** @var Translator */
	protected $translator;

	/** @var LangsService */
	protected $langsService;


	/**
	 * CategoriesService constructor.
	 * @param Orm $orm
	 * @param Nette\Caching\IStorage $s
	 * @param Translator $tr
	 * @param LangsService $lS
	 */
	public function __construct( Orm $orm, Nette\Caching\IStorage $s, Translator $tr, LangsService $lS )
	{
		$this->orm = $orm;
		$this->storage = $s;
		$this->cache = new Nette\Caching\Cache( $this->storage, self::CACHE );
		$this->translator = $tr;
		$this->langsService = $lS;
	}


	/**
	 * @param $category
	 * @return mixed
	 */
	public function switchVisibility( Category $category )
	{
		$newStatus = $category->status == Category::STATUS_PUBLISHED ? Category::STATUS_UNPUBLISHED : Category::STATUS_PUBLISHED;
		$category->status = $newStatus;
		$this->orm->categories->persistAndFlush($category);

		$this->cleanCache();

		return $category;
	}


	/**
	 * @param Category $category
	 * @param array $ids
	 * @return array
	 */
	public function findCategoryTreeIds( Category $category, array $ids = [] )
	{
		$ids[] = $category->id;

		if ( $category->categories->countStored() )
		{
			foreach ( $category->categories as $c )
			{
				$ids = $this->findCategoryTreeIds( $c, $ids );
			}
		}

		return $ids;
	}


	/**
	 * @param $category
	 * @return ResultSet
	 */
	public function findCategoryProducts( $category )
	{
		if( is_numeric( $category ) ) $category = $this->orm->categories->getById( $category );

		$catIds = $this->findCategoryTreeIds( $category );

		$products = $this->orm->products->findBy(['status' => Product::STATUS_PUBLISHED, 'this->categories->id' => $catIds]);

		return $products;
	}


	/**
	 * @desc produces an array of categories in format required by form->select
	 * @param array $cats
	 * @param array $result
	 * @param int $lev
	 * @return array
	 */
	public function categoriesToSelect( $cats = NULL, $result = [], $lev = 0 )
	{
		if ( ! $cats )  // First call.
		{
			$cats = $this->orm->categories->findAdminTopCategories();
		}

		/** @var Category $item */
		foreach ( $cats as $item )
		{
			$result[$item->id] = str_repeat( '--', $lev * 1 ) . '   ' . $item->name;

			if ( $item->adminCategories->countStored() )
			{
				$result = $this->categoriesToSelect( $item->adminCategories, $result, $lev + 1 );
			}
		}

		return $result;
	}


	/**
	 * @param array $params
	 * @return Category
	 * @throws App\Exceptions\DuplicateEntryException
	 * @throws \Exception
	 */
	public function createCategory( array $params )
	{
		//$this->em->beginTransaction();

		try
		{
			$parent = $params['parent'] ? (int)$params['parent'] : NULL;
			$same_level_categories = $this->orm->categories->findBy( [ 'parent' => $parent ] );
			foreach ( $same_level_categories as $cat )
			{
				$cat->priority = $cat->priority + 1;
				$this->orm->categories->persistAndFlush( $cat );
			}

			$category = new Category();
			$category->parent = isset( $params['parent'] ) ? $this->orm->categories->getById( $params['parent'] ) : NULL;
			$category->status = Category::STATUS_UNPUBLISHED;
			$this->orm->categories->persist($category);

			foreach ( $this->langsService->getLangs() as $lang )
			{
				$category_lang = new CategoryLang();
				$category_lang->name = $params['names'][$lang];
				$category_lang->slug = Strings::webalize($category_lang->name);
				$category_lang->lang = $lang;
				$this->orm->categoriesLangs->persist($category_lang);
				$category->langs->add($category_lang);
			}

			$this->orm->categories->persistAndFlush( $category );
		}
		catch ( \Nextras\Dbal\UniqueConstraintViolationException $e )
		{
			throw new App\Exceptions\DuplicateEntryException();
		}

		$this->cleanCache();

		return $category;
	}


	/**
	 * @param $id
	 * @param array $names
	 * @return null|object
	 * @throws App\Exceptions\DuplicateEntryException
	 */
	public function updateName( $id, array $names )
	{
		// No need transaction...
		try
		{
			$category = $this->orm->categories->getById( $id );
			foreach ( $category->langs as $lang )
			{
				$lang->name = $names[$lang->lang];
				$lang->slug = Strings::webalize($lang->name);
				$this->orm->categoriesLangs->persistAndFlush( $lang );
			}
		}
		catch ( \Nextras\Dbal\UniqueConstraintViolationException $e )
		{
			throw new App\Exceptions\DuplicateEntryException( 'Kategória s rovnakým názvom už exituje. Názov musí byť v každnom jazyku unikátny.' );
		}

		$this->cleanCache();

		return $category;

	}


	/**
	 * @param array $arr
	 * @return bool
	 */
	public function updatePriority( array $arr )
	{
		$pairs = $this->orm->categories->findAll()->fetchPairs('id');
		$i = 1;
		foreach ( $arr as $key => $val )
		{
			// if the array is large it would be better to update only changed items but the problem is priority
			$category = $pairs[(int)$key];
			$category->parent = $val == 0 ? NULL : (int)$val;
			$category->priority = $i;

			$this->orm->categories->persistAndFlush($category);

			$i++;
		}

		$this->cleanCache();

		return true;
	}


	/**
	 * @param Category $category
	 * @return array
	 * @throws NoCategoryException
	 */
	public function delete( Category $category )
	{
		if( ! $category ) throw new NoCategoryException( 'Category not found.' );

		$result = $this->canDelete( $category );

		$names = [];
		foreach ( $result['items'] as $item )
		{
			$names[] = $item->name;
			$this->orm->categories->remove( $item );
		}

		$this->orm->categories->flush();

		$this->cleanCache();

		return $names;

	}


	public function cleanCache()
	{
		$langs = $this->langsService->getLangs();
		foreach ( $langs as $lang ) $this->cache->clean( [ Nette\Caching\Cache::TAGS => [ self::CACHE_TAG . $lang ] ] );
	}


//////Protected/Private///////////////////////////////////////////////////////

	/**
	 * @param Category $category
	 * @param null $result
	 * @return array|null
	 * @throws CategoryContainsProductsException
	 */
	protected function canDelete( Category $category, $result = NULL )
	{
		$result = $result ?: [ 'items' => [] ];

		if ( $category->products->countStored() ) throw new CategoryContainsProductsException('Category ' . $category->name . ' can not be deleted. Category contains one or more products.', $category->name);

		foreach ( $category->categories as $child )
		{
			$result = $this->canDelete( $child, $result );
		}

		$result['items'][] = $category;

		return $result;

	}


}

class PartOfAppException extends \Exception
{
	// Entity or nested entity is part of application and so it can not be deleted
}

class NoArticleException extends \Exception
{
	// Entity not found.
}

class NoCategoryException extends \Exception
{
	// Entity not found.
}