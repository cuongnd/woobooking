<?php
/**
 * @package     woobooking.Platform
 * @subpackage  Input
 *
 * @copyright   Copyright (C) 2005-2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace WooBooking\CMS\FOFInput\Input;
defined('_WPBOOKINGPRO_EXEC') or die;

/**
 * woobooking! Input JSON Class
 *
 * This class decodes a JSON string from the raw request data and makes it available via
 * the standard Input interface.
 *
 * @since  12.2
 */
class InputJSON extends Input
{
	/**
	 * @var    string  The raw JSON string from the request.
	 * @since  12.2
	 */
	private $_raw;

	/**
	 * Constructor.
	 *
	 * @param   array  $source   Source data (Optional, default is the raw HTTP input decoded from JSON)
	 * @param   array  $options  Array of configuration parameters (Optional)
	 *
	 * @since   12.2
	 */
	public function __construct(array $source = null, array $options = array())
	{
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }
		if (isset($options['filter']))
		{
			$this->filter = $options['filter'];
		}
		else
		{
			$this->filter = FOFInput::getInstance();
		}

		if (is_null($source))
		{
			$this->_raw = $wp_filesystem->get_contents('php://input');
			$this->data = json_decode($this->_raw, true);
		}
		else
		{
			$this->data = & $source;
		}

		// Set the options for the class.
		$this->options = $options;
	}

	/**
	 * Gets the raw JSON string from the request.
	 *
	 * @return  string  The raw JSON string from the request.
	 *
	 * @since   12.2
	 */
	public function getRaw()
	{
		return $this->_raw;
	}
}
