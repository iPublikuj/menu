<?php
/**
 * ActiveFilter.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Menu!
 * @subpackage	Filter
 * @since		5.0
 *
 * @date		26.08.14
 */

namespace IPub\Menu\Filter;

use Nette;
use Nette\Application;

class ActiveFilter extends FilterIterator
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var Application\IPresenter
	 */
	protected $presenter;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(\Iterator $iterator, array $options = [])
	{
		parent::__construct($iterator, $options);

		// Get application presenter
		$this->presenter = $this->getApplication()->getPresenter();
	}

	/**
	 * {@inheritdoc}
	 */
	public function accept()
	{
		$item = parent::current();

		if ($active = $item->getAttribute('active') and (is_string($active) or is_array($active))) {
			//$isActive = (bool) preg_match('#'.str_replace('*', '.*', $active).'$#', $this->route);

			// Set default value
			$isActive = FALSE;

			// Rules are in array
			if (is_array($active)) {
				// Check all rules
				foreach($active as $rule) {
					try {
						// Try to check rule if is possible
						$isActive = $this->presenter->isLinkCurrent($rule);

					} catch (Application\UI\InvalidLinkException $e) {
						// Presenter for checked route does not exists
					}

					// One from the rules was successful
					if ($isActive) {
						break;
					}
				}

			// One single rule
			} else {
				try {
					// Try to check rule if is possible
					$isActive = $this->presenter->isLinkCurrent($active);

				} catch (Application\UI\InvalidLinkException $e) {
					// Presenter for checked route does not exists
				}
			}

			// Update item attribute
			$item->setAttribute('isActive', $isActive);

			if ($isActive) {
				// And update all parents
				while ($item->getParentId() && $item = $item->getMenu()->getItem($item->getParentId())) {
					$item->setAttribute('isActive', $active);
				}
			}
		}

		return TRUE;
	}
}