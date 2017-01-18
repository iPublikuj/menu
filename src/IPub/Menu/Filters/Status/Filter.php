<?php
/**
 * Filter.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Filter
 * @since          1.0.0
 *
 * @date           26.08.14
 */

namespace IPub\Menu\Filters\Status;

use Nette;
use Nette\Application;

use IPub;
use IPub\Menu\Entities;
use IPub\Menu\Filters;

/**
 * Menu is active filter
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
	public function __construct(\Iterator $iterator, array $options = [], Application\Application $application)
	{
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

		if (!$item->hasAbsoluteTarget() && ($active = $item->getAttribute('active')) && (is_string($active) || is_array($active))) {
			// Set default value
			$isActive = FALSE;

			// Rules are in array
			if (is_array($active)) {
				// Check all rules
				foreach ($active as $rule) {
					$isActive = $this->checkRule($rule);

					// One from the rules was successful
					if ($isActive) {
						break;
					}
				}

			// One single rule
			} else {
				$isActive = $this->checkRule($active);
			}

			// Update item attribute
			$item->setAttribute('isActive', $isActive);

			if ($isActive) {
				// And update all parents
				while ($item->getParentId() && ($item = $item->getMenu()->getItem($item->getParentId()))) {
					$item->setAttribute('isActive', $active);
				}
			}
		}

		return TRUE;
	}

	/**
	 * @param string $rule
	 *
	 * @return bool
	 */
	private function checkRule($rule) : bool
	{
		try {
			// Try to check rule if is possible
			return $this->presenter->isLinkCurrent($rule);

		} catch (Application\UI\InvalidLinkException $e) {
			// Presenter for checked route does not exists
		}
	}
}
