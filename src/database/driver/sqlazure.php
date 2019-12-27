<?php
/**
 * @package     woobooking.Platform
 * @subpackage  Database
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace Woobooking\CMS\Database\driver;
defined('_WOO_BOOKING_EXEC') or die;
/**
 * SQL Server database driver
 *
 * @see    https://azure.microsoft.com/en-us/documentation/services/sql-database/
 * @since  12.1
 */
class DatabaseDriverSqlazure extends DatabaseDriverSqlsrv
{
	/**
	 * The name of the database driver.
	 *
	 * @var    string
	 * @since  12.1
	 */
	public $name = 'sqlazure';
}
