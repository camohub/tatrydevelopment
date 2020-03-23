<?php

declare(strict_types=1);

namespace App\Router;

use Kdyby\Translation\Translator;
use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Tracy\Debugger;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter( Translator $t ): RouteList
	{
		$router = new RouteList();
		$locales = self::getLocales($t);

		$router[] = $adminRouter = new RouteList('Admin');
		$router[] = $frontRouter = new RouteList('Front');

		$adminRouter->addRoute('admin/<presenter>/<action>[/<id>]', 'Default:default');


		$frontRouter->addRoute( '[<locale>/][<presenter>[/<action>]]',
			[
				'locale' => [
					Route::PATTERN => join('|', $locales),
					Route::VALUE => 'sk',
				],
				'presenter' => [
					Route::PATTERN => 'ponuka|offer',
					Route::VALUE => 'ponuka',
				],
				'action' => [
					Route::VALUE => 'default',
				],
				NULL => [
					Route::FILTER_IN => function ($params) {
						//Debugger::barDump($params, 'IN');
						$params['presenter'] = 'Default';
						return $params;
					},
					Route::FILTER_OUT => function ($params) {
						//Debugger::barDump( $params, 'OUT' );
						$locale = $params['locale'] ?? 'sk';
						$tr = ['sk' => 'ponuka', 'cs' => 'ponuka', 'en' => 'offer'];
						$params['presenter'] = $tr[$locale];
						return $params;
					}
				]
			]
		);

		$frontRouter[] = new Route( '[<locale=sk sk|cs|en>/]<presenter>/<action>[/<id>]' );

		return $router;
	}

	public static function getLocales(Translator $t)
	{
		$result = [];
		foreach ($t->getAvailableLocales() as $l) $result[] = strtolower(explode('_', $l)[0]);
		return $result;
	}
}
