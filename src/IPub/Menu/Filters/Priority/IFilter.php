<?php
/**
 * IFilter.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Menu!
 * @subpackage     Filters
 * @since          1.0.0
 *
 * @date           08.12.16
 */

declare(strict_types = 1);

namespace IPub\Menu\Filters\Priority;

use IPub\Menu\Filters;

/**
 * Menu priority filter factory
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
