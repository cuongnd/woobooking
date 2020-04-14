<?php
/**
 * woobooking! Content Management System
 *
 * Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace WooBooking\CMS\Client;

defined('_WPBOOKINGPRO_EXEC') or die;

use woobooking\CMS\Log\Log;
use woobooking\CMS\Utility\BufferStreamHandler;

/** Error Codes:
 * - 30 : Unable to connect to host
 * - 31 : Not connected
 * - 32 : Unable to send command to server
 * - 33 : Bad username
 * - 34 : Bad password
 * - 35 : Bad response
 * - 36 : Passive mode failed
 * - 37 : Data transfer error
 * - 38 : Local filesystem error
 */

if (!defined('CRLF'))
{
	/**
	 * Constant defining a line break
	 *
	 * @var    string
	 * @since  1.5
	 */
	define('CRLF', "\r\n");
}

if (!defined('FTP_AUTOASCII'))
{
	/**
	 * Constant defining whether the FTP connection type will automatically determine ASCII support based on a file extension
	 *
	 * @var    integer
	 * @since  1.5
	 */
	define('FTP_AUTOASCII', -1);
}

if (!defined('FTP_BINARY'))
{
	/**
	 * Stub of the native FTP_BINARY constant if PHP is running without the ftp extension enabled
	 *
	 * @var    integer
	 * @since  1.5
	 */
	define('FTP_BINARY', 1);
}

if (!defined('FTP_ASCII'))
{
	/**
	 * Stub of the native FTP_ASCII constant if PHP is running without the ftp extension enabled
	 *
	 * @var    integer
	 * @since  1.5
	 */
	define('FTP_ASCII', 0);
}

if (!defined('FTP_NATIVE'))
{
	/**
	 * Constant defining whether native FTP support is available on the platform
	 *
	 * @var    integer
	 * @since  1.5
	 */
	define('FTP_NATIVE', function_exists('ftp_connect') ? 1 : 0);
}

/**
 * FTP client class
 *
 * @since  1.5
 */
class FtpClient
{
	/**
	 * @var    resource  Socket resource
	 * @since  1.5
	 */
	protected $_conn = null;

	/**
	 * @var    resource  Data port connection resource
	 * @since  1.5
	 */
	protected $_dataconn = null;

	/**
	 * @var    array  Passive connection information
	 * @since  1.5
	 */
	protected $_pasv = null;

	/**
	 * @var    string  Response Message
	 * @since  1.5
	 */
	protected $_response = null;

	/**
	 * @var    integer  Timeout limit
	 * @since  1.5
	 */
	protected $_timeout = 15;

	/**
	 * @var    integer  Transfer Type
	 * @since  1.5
	 */
	protected $_type = null;

	/**
	 * @var    array  Array to hold ascii format file extensions
	 * @since  1.5
	 */
	protected $_autoAscii = array(
		'asp',
		'bat',
		'c',
		'cpp',
		'csv',
		'h',
		'htm',
		'html',
		'shtml',
		'ini',
		'inc',
		'log',
		'php',
		'php3',
		'pl',
		'perl',
		'sh',
		'sql',
		'txt',
		'xhtml',
		'xml',
	);

	/**
	 * Array to hold native line ending characters
	 *
	 * @var    array
	 * @since  1.5
	 */
	protected $_lineEndings = array('UNIX' => "\n", 'WIN' => "\r\n");

	/**
	 * @var    array  FtpClient instances container.
	 * @since  2.5
	 */
	protected static $instances = array();

	/**
	 * FtpClient object constructor
	 *
	 * @param   array  $options  Associative array of options to set
	 *
	 * @since   1.5
	 */
	public function __construct(array $options = array())
	{
		// If default transfer type is not set, set it to autoascii detect
		if (!isset($options['type']))
		{
			$options['type'] = FTP_BINARY;
		}

		$this->setOptions($options);

		if (FTP_NATIVE)
		{
			BufferStreamHandler::stream_register();
		}
	}

	/**
	 * FtpClient object destructor
	 *
	 * Closes an existing connection, if we have one
	 *
	 * @since   1.5
	 */
	public function __destruct()
	{
		if (is_resource($this->_conn))
		{
			$this->quit();
		}
	}

	/**
	 * Returns the global FTP connector object, only creating it
	 * if it doesn't already exist.
	 *
	 * You may optionally specify a username and password in the parameters. If you do so,
	 * you may not login() again with different credentials using the same object.
	 * If you do not use this option, you must quit() the current connection when you
	 * are done, to free it for use by others.
	 *
	 * @param   string  $host     Host to connect to
	 * @param   string  $port     Port to connect to
	 * @param   array   $options  Array with any of these options: type=>[FTP_AUTOASCII|FTP_ASCII|FTP_BINARY], timeout=>(int)
	 * @param   string  $user     Username to use for a connection
	 * @param   string  $pass     Password to use for a connection
	 *
	 * @return  FtpClient        The FTP Client object.
	 *
	 * @since   1.5
	 */
	public static function getInstance($host = '127.0.0.1', $port = '21', array $options = array(), $user = null, $pass = null)
	{
		$signature = $user . ':' . $pass . '@' . $host . ':' . $port;

		// Create a new instance, or set the options of an existing one
		if (!isset(static::$instances[$signature]) || !is_object(static::$instances[$signature]))
		{
			static::$instances[$signature] = new static($options);
		}
		else
		{
			static::$instances[$signature]->setOptions($options);
		}

		// Connect to the server, and login, if requested
		if (!static::$instances[$signature]->isConnected())
		{
			$return = static::$instances[$signature]->connect($host, $port);

			if ($return && $user !== null && $pass !== null)
			{
				static::$instances[$signature]->login($user, $pass);
			}
		}

		return static::$instances[$signature];
	}

	/**
	 * Set client options
	 *
	 * @param   array  $options  Associative array of options to set
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   1.5
	 */
	public function setOptions(array $options)
	{
		if (isset($options['type']))
		{
			$this->_type = $options['type'];
		}

		if (isset($options['timeout']))
		{
			$this->_timeout = $options['timeout'];
		}

		return true;
	}

	/**
	 * Method to connect to a FTP server
	 *
	 * @param   string  $host  Host to connect to [Default: 127.0.0.1]
	 * @param   string  $port  Port to connect on [Default: port 21]
	 *
	 * @return  boolean  True if successful
	 *
	 * @since   3.0.0
	 */

}
