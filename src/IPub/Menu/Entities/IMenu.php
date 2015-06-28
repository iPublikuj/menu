<?php
/**
 * IMenu.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Menu!
 * @subpackage	Entities
 * @since		5.0
 *
 * @date		20.08.14
 */

namespace IPub\Menu\Entities;

interface IMenu
{
	/**
	 * @return string
	 */
	public function getId();

	/**
	 * @param string
	 */
	public function setId($id);

	/**
	 * Add a new menu item instance.
	 *
	 * @param  IItem $item
	 */
	public function addItem(IItem $item);

	/**
	 * Get a menu item instance.
	 *
	 * @param  string $id
	 * @return IItem
	 */
	public function getItem($id);

	/**
	 * @param param IItem[] $items
	 */
	public function setItems(array $items = []);

	/**
	 * @return IItem[]
	 */
	public function getItems();

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator();
}