<?php
/**
 * FilterIterator.php
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

abstract class FilterIterator extends \FilterIterator
{
	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @var Application\Application
	 */
	protected static $app;

	/**
	 * Gets the application.
	 *
	 * @return Application\Application
	 */
	public function getApplication()
	{
		return self::$app;
	}

	/**
	 * Sets the application.
	 *
	 * @param Application\Application $app
	 */
	public static function setApplication(Application\Application $app)
	{
		self::$app = $app;
	}

	/**
	 * Constructor
	 *
	 * @param \Iterator $iterator
	 * @param array $options
	 */
	public function __construct(\Iterator $iterator, array $options = [])
	{
		parent::__construct($iterator);

		$this->options = $options;
	}
}
