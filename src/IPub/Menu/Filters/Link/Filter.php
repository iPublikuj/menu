<?php
/**
 * Filter.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Menu!
 * @subpackage     Filter
 * @since          1.0.0
 *
 * @date           26.08.14
 */

namespace IPub\Menu\Filters\Link;

use Nette\Application;

use IPub\Menu\Entities;
use IPub\Menu\Exceptions;
use IPub\Menu\Filters;

/**
 * Menu link filter
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Filters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class Filter extends Filters\FilterIterator
{
	/**
	 * @var Application\IPresenter|Application\UI\Presenter
	 */
	private $presenter;

	/**
	 * @param \Iterator $iterator
	 * @param array $options
	 * @param Application\Application $application
	 */
	public function __construct(
		\Iterator $iterator,
		array $options = [],
		Application\Application $application
	) {
		parent::__construct($iterator, $options);

		$this->presenter = $application->getPresenter();
	}

	/**
	 * {@inheritdoc}
	 */
	public function accept()
	{
		/** @var Entities\Items\IItem $item */
		$item = parent::current();

		if ($item->getTarget() && !$item->hasAbsoluteTarget()) {
			list($destination, $params) = $this->parseTargetValue($item->getTarget());

			$item->setAttribute('link', $this->presenter->link($destination, $params));
		}

		return TRUE;
	}

	/**
	 * @param string|array $target
	 *
	 * @return array
	 */
	private function parseTargetValue($target) : array
	{
		$args = [];

		if (is_array($target) && isset($target[0]) && isset($target[1]) && is_string($target[0]) && is_array($target[1])) {
			$args = array_merge($args, $target[1]);
			$destination = $target[0];

		} elseif (is_string($target)) {
			$destination = $target;

		} else {
			throw new Exceptions\InvalidArgumentException();
		}

		return [
			$destination,
			$args
		];
	}
}
