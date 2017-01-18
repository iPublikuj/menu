<?php
/**
 * IMenu.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Entities
 * @since          1.0.0
 *
 * @date           20.08.14
 */

declare(strict_types = 1);

namespace IPub\Menu\Entities\Menus;

use IPub;
use IPub\Menu\Entities;

/**
 * Menu container interface
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IMenu extends \ArrayAccess, \IteratorAggregate
{
	/**
	 * @return string|int
	 */
	function getId();

	/**
	 * @return string
	 */
	function getName() : string;

	/**
	 * @param Entities\Items\IItem[] $items
	 */
	function setItems(array $items = []);

	/**
	 * @param Entities\Items\IItem $item
	 *
	 * @return void
	 */
	function addItem(Entities\Items\IItem $item);

	/**
	 * @return Entities\Items\IItem[]
	 */
	function getItems() : array;

	/**
	 * @param string|int $id
	 *
	 * @return Entities\Items\IItem
	 */
	function getItem($id) : Entities\Items\IItem;

	/**
	 * @param string|int $id
	 *
	 * @return bool
	 */
	function hasItem($id) : bool;
}
