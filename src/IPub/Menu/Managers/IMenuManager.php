<?php
/**
 * MenuManager.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Managers
 * @since          1.0.0
 *
 * @date           24.08.14
 */

declare(strict_types = 1);

namespace IPub\Menu\Managers;

use IPub;
use IPub\Menu\Entities;

/**
 * Menus manager interface
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Managers
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IMenuManager extends \ArrayAccess, \IteratorAggregate
{
	/**
	 * Checks whether a menu is registered
	 *
	 * @param string|int $id
	 *
	 * @return bool
	 */
	public function has($id);

	/**
	 * Gets a menu
	 *
	 * @param string|int $id
	 *
	 * @return Entities\Menus\IMenu
	 */
	public function get($id);

	/**
	 * Helper method for DI connection
	 *
	 * @param Entities\Menus\IMenu|string $menu
	 * @param Entities\Items\IItem|string $item
	 * @param string|NULL $name
	 * @param array $parameters
	 *
	 * @return void
	 */
	public function addItem($menu, $item, string $name = NULL, array $parameters = []);

	/**
	 * Retrieves menu item tree
	 *
	 * @param string|Entities\Menus\IMenu $menu
	 * @param array $parameters
	 *
	 * @return Entities\Nodes\Node
	 */
	public function getTree($menu, array $parameters = []);
}
