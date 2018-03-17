<?php
/**
 * Filter.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Filters
 * @since          1.0.0
 *
 * @date           26.06.15
 */

declare(strict_types = 1);

namespace IPub\Menu\Filters\Priority;

use IPub\Menu\Entities;
use IPub\Menu\Filters;

/**
 * Menu priority filter
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Filters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class Filter extends Filters\FilterIterator
{
	/**
	 * {@inheritdoc}
	 */
	public function __construct(\Iterator $iterator, array $options = [])
	{
		/** @var Entities\Items\IItem[] $elements */
		$elements = iterator_to_array($iterator, FALSE);

		$iterator->uasort(function ($a, $b) use ($elements) : int {

			$priorityA = (int) $a->getPriority();
			$priorityB = (int) $b->getPriority();

			if ($priorityA == $priorityB) {
				$priorityA = array_search($a, $elements);
				$priorityB = array_search($b, $elements);
			}

			return ($priorityA < $priorityB) ? -1 : 1;
		});

		parent::__construct($iterator, $options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function accept()
	{
		return TRUE;
	}
}
