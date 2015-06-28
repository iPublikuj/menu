<?php
/**
 * Menu.php
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

use Nette;
use Nette\Application;
use Nette\Localization;
use Nette\Security;

class Menu extends Nette\Object implements IMenu
{
	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $items;

	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function setId($id)
	{
		$this->id = (string) $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string
	 *
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = (string) $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param IItem $item
	 *
	 * @return $this
	 */
	public function addItem(IItem $item)
	{
		// Add menu factory
		$item->setMenu($this);

		$this->items[$item->getId()] = $item;

		return $this;
	}

	/**
	 * @param $id
	 *
	 * @return IItem
	 */
	public function getItem($id)
	{
		return isset($this->items[$id]) ? $this->items[$id] : null;
	}

	/**
	 * @param array $items
	 *
	 * @return $this
	 */
	public function setItems(array $items = [])
	{
		$this->items = $items;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getItems()
	{
		return (array) $this->items;
	}

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->getItems());
	}
}