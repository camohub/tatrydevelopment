<?php


namespace App\Model\Services;


use App;
use App\Exceptions\CategoryContainsProductsException;
use App\Model\Entity;
use App\Model\Orm\Category;
use App\Model\Orm\CategoryLang;
use App\Model\Orm\Orm;
use App\Model\Orm\Parameter;
use App\Model\Orm\ParameterLang;
use App\Model\Repositories\ArticlesRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Kdyby\Doctrine\ResultSet;
use Kdyby\Translation\Translator;
use Nette;
use Nette\Utils\Strings;
use App\Model\Repositories\CategoriesArticlesRepository;
use App\Model\Repositories\ArticlesCategoriesArticlesRepository;
use Tracy\Debugger;


class ParametersService
{
	/** @var Orm */
	protected $orm;

	/** @var LangsService  */
	protected $langsService;


	/**
	 * ParametersService constructor.
	 * @param Orm $orm
	 */
	public function __construct( Orm $orm, LangsService $lS )
	{
		$this->orm = $orm;
		$this->langsService = $lS;
	}


	public function createParameter( array $values )
	{
		$param = new Parameter();
		$param->type = $values['type'];

		foreach ($values['names'] as $k => $v)
		{
			$lang = new ParameterLang();
			$lang->lang = $k;
			$lang->name = $v;
			$lang->parameter = $param;
			$this->orm->parametersLangs->persist($lang);
			$param->langs->add($lang);
		}

		$this->orm->parameters->persistAndFlush($param);
	}


	/**
	 * @return array
	 */
	public function parametersToSelect()
	{
		$result = [];
		$paramsLangs = $this->orm->parametersLangs->findBy(['lang' => 'sk'])->orderBy('name', 'ASC');

		/** @var ParameterLang $pL */
		foreach ($paramsLangs as $pL) $result[$pL->getRawValue('parameter')] = $pL->name;

		return $result;
	}
}