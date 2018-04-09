<?php
/**
 * Filter.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Menu!
 * @subpackage     Filters
 * @since          1.0.0
 *
 * @date           16.01.17
 */

declare(strict_types = 1);

namespace IPub\Menu\Filters\Access;

use Nette\Utils;
use Nette\Security as NS;

use IPub;
use IPub\Menu\Entities;
use IPub\Menu\Exceptions;
use IPub\Menu\Filters;

/**
 * Menu access filter
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Filters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class Filter extends Filters\FilterIterator
{
	/**
	 * @var NS\User
	 */
	private $user;

	/**
	 * @param \Iterator $iterator
	 * @param array $options
	 * @param NS\User $user
	 */
	public function __construct(\Iterator $iterator, array $options = [], NS\User $user)
	{
		parent::__construct($iterator, $options);

		$this->user = $user;
	}

	/**
	 * {@inheritdoc}
	 */
	public function accept()
	{
		/** @var Entities\Items\IItem $item */
		$item = parent::current();

		$access = $item->getAttribute('access', []);

		if (!is_array($access) || $access === []) {
			return TRUE;
		}

		$isAllowed = TRUE;

		foreach ($access as $accessSettings) {
			list($type, $rule) = explode('@', $accessSettings) + [NULL, NULL];

			$rule = Utils\Strings::trim($rule);

			if ($rule === '') {
				continue;
			}

			switch (strtolower($type))
			{
				case 'user':
					$isAllowed = $isAllowed && $this->checkUser($rule);
					break;

				case 'resource':
					$isAllowed = $isAllowed && $this->checkResources($rule, $access);
					break;

				case 'privilege':
					$isAllowed = $isAllowed && $this->checkPrivileges($rule);
					break;

				case 'permission':
					$isAllowed = $isAllowed && $this->checkPermission($rule);
					break;

				case 'role':
					$isAllowed = $isAllowed && $this->checkRoles($rule);
					break;
			}
		}

		return $isAllowed;
	}

	/**
	 * @param string $rule
	 *
	 * @return bool
	 */
	private function checkUser(string $rule) : bool
	{
		// Get user rule
		$user = Utils\Strings::trim($rule);

		// Rule is single string
		if (is_string($user) && in_array($user, ['loggedIn', 'guest'], TRUE)) {
			// User have to be logged in and is not
			if ($user === 'loggedIn' && $this->user->isLoggedIn() === FALSE) {
				return FALSE;

			// User have to be logged out and is logged in
			} elseif ($user === 'guest' && $this->user->isLoggedIn() === TRUE) {
				return FALSE;
			}

		// Rule has wrong definition
		} else {
			throw new Exceptions\InvalidArgumentException('In "user" access settings is allowed only one from two strings: \'loggedIn\' & \'guest\'');
		}

		return TRUE;
	}

	/**
	 * @param string $rule
	 * @param array $access
	 *
	 * @return bool
	 */
	private function checkResources(string $rule, array $access = []) : bool
	{
		$resources = preg_split ('/[\s*,\s*]*,+[\s*,\s*]*/', $rule);

		if (count($resources) != 1) {
			throw new Exceptions\InvalidStateException('Invalid resources count in access settings!');
		}

		$privileges = isset($access['privilege']) ? preg_split ('/[\s*,\s*]*,+[\s*,\s*]*/', $access['privilege']) : [];

		foreach ($resources as $resource) {
			if ($privileges !== []) {
				foreach ($privileges as $privilege) {
					if ($this->user->isAllowed($resource, $privilege)) {
						return TRUE;
					}
				}

			} else {
				if ($this->user->isAllowed($resource)) {
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	/**
	 * @param string $rule
	 *
	 * @return bool
	 */
	private function checkPrivileges(string $rule) : bool
	{
		$privileges = preg_split ('/[\s*,\s*]*,+[\s*,\s*]*/', $rule);

		if (count($privileges) != 1) {
			throw new Exceptions\InvalidStateException('Invalid privileges count in access settings!');
		}

		foreach ($privileges as $privilege) {
			if ($this->user->isAllowed(NS\IAuthorizator::ALL, $privilege)) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * @param string $rule
	 *
	 * @return bool
	 */
	private function checkPermission(string $rule) : bool
	{
		$permissions = preg_split ('/[\s*,\s*]*,+[\s*,\s*]*/', $rule);

		$permissionDelimiter = ':';

		if (interface_exists('\IPub\Permissions\Entities\IPermission')) {
			$permissionDelimiter = IPub\Permissions\Entities\IPermission::DELIMITER;
		}

		foreach ($permissions as $permission) {
			// Parse resource & privilege from permission
			list($resource, $privilege) = explode($permissionDelimiter, $permission) + [NULL, NULL];

			// Remove white spaces
			$resource = Utils\Strings::trim($resource);
			$privilege = Utils\Strings::trim($privilege);

			if ($this->user->isAllowed($resource, $privilege)) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * @param string $rule
	 *
	 * @return bool
	 */
	private function checkRoles(string $rule) : bool
	{
		$roles = preg_split ('/[\s*,\s*]*,+[\s*,\s*]*/', $rule);

		foreach ($roles as $role) {
			if ($this->user->isInRole($role)) {
				return TRUE;
			}
		}

		return FALSE;
	}
}
