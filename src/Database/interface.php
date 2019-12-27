<?php
/**
 * @package     woobooking.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace WooBooking\CMS\Database;
defined('_WOO_BOOKING_EXEC') or die;
/**
 * woobooking Platform Database Interface
 *
 * @since  11.2
*/
interface DatabaseInterface
{
	/**
	 * Test to see if the connector is available.
	 *
	 * @return  boolean  True on success, false otherwise.
	 *
	 * @since   11.2
	 */
	public static function isSupported();
}
