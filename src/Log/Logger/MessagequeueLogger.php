<?php
/**
 * woobooking! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace WooBooking\CMS\Log\Logger;

defined('_WOO_BOOKING_EXEC') or die;

use WooBooking\CMS\Log\Log;
use WooBooking\CMS\Log\LogEntry;
use WooBooking\CMS\Log\Logger;

/**
 * woobooking MessageQueue logger class.
 *
 * This class is designed to output logs to a specific MySQL database table. Fields in this
 * table are based on the Syslog style of log output. This is designed to allow quick and
 * easy searching.
 *
 * @since  1.7.0
 */
class MessagequeueLogger extends Logger
{
	/**
	 * Method to add an entry to the log.
	 *
	 * @param   LogEntry  $entry  The log entry object to add to the log.
	 *
	 * @return  void
	 *
	 * @since   1.7.0
	 */
	public function addEntry(LogEntry $entry)
	{
		switch ($entry->priority)
		{
			case Log::EMERGENCY:
			case Log::ALERT:
			case Log::CRITICAL:
			case Log::ERROR:
				\Factory::getApplication()->enqueueMessage($entry->message, 'error');
				break;
			case Log::WARNING:
				\Factory::getApplication()->enqueueMessage($entry->message, 'warning');
				break;
			case Log::NOTICE:
				\Factory::getApplication()->enqueueMessage($entry->message, 'notice');
				break;
			case Log::INFO:
				\Factory::getApplication()->enqueueMessage($entry->message, 'message');
				break;
			default:
				// Ignore other priorities.
				break;
		}
	}
}
