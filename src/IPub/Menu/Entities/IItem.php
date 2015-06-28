<?php
/**
 * IItem.php
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

interface IItem
{
	/**
	 * @var int Item deactivated
	 */
	const STATUS_DEACTIVATED = 0;

	/**
	 * @var int Item active
	 */
	const STATUS_ACTIVE = 1;

	/**
	 * @return string
	 */
	public function getId();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return string
	 */
	public function getRoute();

	/**
	 * @return mixed
	 */
	public function getParentId();

	/**
	 * Returns the items attributes
	 *
	 * @return array
	 */
	public function getAttributes();

	/**
	 * @param string $name
	 * @param mixed $default
	 */
	public function getAttribute($name, $default = null);

	/**
	 * Gets item's data array
	 */
	public function getData();

	/**
	 * Gets a data value
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function get($key, $default = null);

	/**
	 * @return IMenu
	 */
	public function getMenu();

	/**
	 * @return string
	 */
	public function __toString();
}