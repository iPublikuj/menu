<?php
/**
 * IMenuItemsProvider.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Menu!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		20.08.14
 */

namespace IPub\Menu\DI;

use Nette;

use IPub;

interface IMenuItemsProvider
{
	/**
	 * Return array of menu items
	 *
	 * @return array
	 */
	function getMenuItems();
}
