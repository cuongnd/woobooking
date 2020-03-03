<?php
/**
 * @package     woobooking.Platform
 * @subpackage  Session
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_WPBOOKINGPRO_EXEC') or die;

/**
 * APC session storage handler for PHP
 *
 * @link        https://www.php.net/manual/en/function.session-set-save-handler.php
 * @since       1.7.0
 * @deprecated  4.0  The CMS' Session classes will be replaced with the `woobooking/session` package
 */
class SessionStorageApc extends SessionStorage
{
	/**
	 * Constructor
	 *
	 * @param   array  $options  Optional parameters
	 *
	 * @since   1.7.0
	 * @throws  RuntimeException
	 */
	public function __construct($options = array())
	{
		if (!self::isSupported())
		{
			throw new RuntimeException('APC Extension is not available', 404);
		}

		parent::__construct($options);
	}

	/**
	 * Read the data for a particular session identifier from the
	 * SessionHandler backend.
	 *
	 * @param   string  $id  The session identifier.
	 *
	 * @return  string  The session data.
	 *
	 * @since   1.7.0
	 */
	public function read($id)
	{
		$sess_id = 'sess_' . $id;

		return (string) apc_fetch($sess_id);
	}

	/**
	 * Write session data to the SessionHandler backend.
	 *
	 * @param   string  $id            The session identifier.
	 * @param   string  $session_data  The session data.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   1.7.0
	 */
	public function write($id, $session_data)
	{
		$sess_id = 'sess_' . $id;

		return apc_store($sess_id, $session_data, ini_get('session.gc_maxlifetime'));
	}

	/**
	 * Destroy the data for a particular session identifier in the SessionHandler backend.
	 *
	 * @param   string  $id  The session identifier.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   1.7.0
	 */
	public function destroy($id)
	{
		$sess_id = 'sess_' . $id;

		return apc_delete($sess_id);
	}

	/**
	 * Test to see if the SessionHandler is available.
	 *
	 * @return boolean  True on success, false otherwise.
	 *
	 * @since   3.0.0
	 */
	public static function isSupported()
	{
		return extension_loaded('apc');
	}
}
