<?php
/**
 * Item.php
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

class Item extends Nette\Object implements IItem
{
	/**
	 * @var string
	 */
	public $id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $label;

	/**
	 * @var string
	 */
	public $icon;

	/**
	 * @var string
	 */
	public $route;

	/**
	 * @var string
	 */
	public $access;

	/**
	 * @var array
	 */
	public $attributes = [];

	/**
	 * @var int
	 */
	public $priority;

	/**
	 * @var IMenu
	 */
	public $menu;

	/**
	 * @var int
	 */
	public $parentId;

	/**
	 * @var IItem
	 */
	public $parent;

	/**
	 * @var array
	 */
	public $data = [];

	/**
	 * @param array $properties
	 */
	public function __construct($properties = [])
	{
		foreach ($properties as $property => $value) {
			if (property_exists($this, $property)) {
				$this->__set($property, $value);

			} else {
				$this->setAttribute($property, $value);
			}
		}
	}

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
	 * @param string $label
	 *
	 * @return $this
	 */
	public function setLabel($label)
	{
		$this->label = (string) $label;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param string $icon
	 *
	 * @return $this
	 */
	public function setIcon($icon)
	{
		$this->icon = (string) $icon;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @param string $route
	 *
	 * @return $this
	 */
	public function setRoute($route)
	{
		$this->route = $route;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRoute()
	{
		return $this->route;
	}

	/**
	 * @param string $access
	 *
	 * @return $this
	 */
	public function setAccess($access)
	{
		$this->access = (string) $access;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAccess()
	{
		return $this->access;
	}

	/**
	 * Sets the item's attributes
	 *
	 * @param array $attributes
	 *
	 * @return $this
	 */
	public function setAttributes(array $attributes)
	{
		$this->attributes = $attributes;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;

		return $this;
	}

	/**
	 * @param string $name
	 * @param null $default
	 *
	 * @return null
	 */
	public function getAttribute($name, $default = null)
	{
		return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
	}

	/**
	 * @param mixed $parentId
	 *
	 * @return $this
	 */
	public function setParentId($parentId)
	{
		$this->parentId = $parentId;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 * @param int $priority
	 *
	 * @return $this
	 */
	public function setPriority($priority)
	{
		$this->priority = (int) $priority;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPriority()
	{
		return (int) $this->priority;
	}

	/**
	 * @param IMenu $menu
	 *
	 * @return $this
	 */
	public function setMenu(IMenu $menu)
	{
		$this->menu = $menu;

		return $this;
	}

	/**
	 * @return IMenu
	 */
	public function getMenu()
	{
		return $this->menu;
	}

	/**
	 * @return bool
	 */
	public function isActive()
	{
		return $this->getAttribute('isActive', FALSE);
	}

	/**
	 * @param array $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param  string $key
	 * @param  mixed  $default
	 *
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return isset($this->data[$key]) ? $this->data[$key] : $default;
	}

	/**
	 * @param string $key
	 * @param mixed  $value
	 */
	public function set($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->label;
	}
}