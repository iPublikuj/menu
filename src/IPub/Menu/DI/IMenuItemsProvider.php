<?php
/**
 * IMenuItemsProvider.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           20.08.14
 */

declare(strict_types = 1);

namespace IPub\Menu\DI;

/**
 * Extension interface for automatic menu items loading
 *
 * @package        iPublikuj:Menu!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IMenuItemsProvider
{
	/**
	 * Return array of menu items
	 *
	 * Structure:
	 *
	 * return [
	 *    menuName => [
	 *        itemName => [
	 *        ],
	 *        anotherItemName => [
	 *        ]
	 *    ],
	 *    anotherMenuName => [
	 *        itemName => [
	 *        ],
	 *    ],
	 * ];
	 *
	 * @return array
	 */
	function getMenuItems();
}
