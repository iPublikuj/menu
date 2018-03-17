<?php
/**
 * Test: IPub\Menu\Libraries
 * @testCase
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Tests
 * @since          2.0.0
 *
 * @date           15.01.17
 */

declare(strict_types = 1);

namespace IPubTests\Menu\Libs;

use Nette;
use Nette\Application;
use Nette\Application\Routers;

/**
 * Simple routes factory
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Tests
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class RouterFactory
{
	/**
	 * @return Application\IRouter
	 */
	public static function createRouter() : Application\IRouter
	{
		$router = new Routers\RouteList();
		$router[] = new Routers\Route('//www.ipublikuj.eu/<presenter>/<action>[/<id>]', 'Test:default');

		return $router;
	}
}
