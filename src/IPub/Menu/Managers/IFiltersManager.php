<?php
/**
 * IFiltersManager.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Menu!
 * @subpackage     Managers
 * @since          1.0.0
 *
 * @date           26.06.15
 */

declare(strict_types = 1);

namespace IPub\Menu\Managers;

use IPub\Menu\Filters;

/**
 * Menus filters manager interface
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Managers
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IFiltersManager extends \ArrayAccess, \IteratorAggregate
{
	/**
	 * Check if a filter is registered
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	function has(string $name) : bool;

	/**
	 * Returns a registered filter class
	 *
	 * @param string $name
	 *
	 * @return Filters\IFactory|NULL
	 */
	function get(string $name) : ?Filters\IFactory;

	/**
	 * Register a filter class name
	 *
	 * @param Filters\IFactory $filter
	 * @param string $name
	 * @param int $priority
	 *
	 * @return void
	 */
	function register(Filters\IFactory $filter, string $name, int $priority = 0) : void;

	/**
	 * Unregisters a filter
	 *
	 * @param string $name
	 *
	 * @return void
	 */
	function unregister(string $name) : void;
}
