<?php
/**
 * IFilter.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Filters
 * @since          1.0.0
 *
 * @date           16.01.17
 */

declare(strict_types = 1);

namespace IPub\Menu\Filters\Link;

use IPub;
use IPub\Menu\Filters;

/**
 * Menu link filter factory
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Filters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IFilter extends Filters\IFactory
{
	/**
	 * @param \Iterator $iterator
	 * @param array $options
	 *
	 * @return Filter
	 */
	public function create(\Iterator $iterator, array $options = []) : Filter;
}
