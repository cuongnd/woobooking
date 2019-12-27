<?php
/**
 * @package     woobooking.Platform
 * @subpackage  Language
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_WOO_BOOKING_EXEC') or die;
/**
 * Wrapper class for LanguageHelper
 *
 * @package     woobooking.Platform
 * @subpackage  Language
 * @since       3.4
 */
class LanguageWrapperHelper
{
	/**
	 * Helper wrapper method for createLanguageList
	 *
	 * @param   string   $actualLanguage  Client key for the area.
	 * @param   string   $basePath        Base path to use.
	 * @param   boolean  $caching         True if caching is used.
	 * @param   boolean  $installed       Get only installed languages.
	 *
	 * @return  array  List of system languages.
	 *
	 * @see     LanguageHelper::createLanguageList
	 * @since   3.4
	 */
	public function createLanguageList($actualLanguage, $basePath = JPATH_BASE, $caching = false, $installed = false)
	{
		return LanguageHelper::createLanguageList($actualLanguage, $basePath, $caching, $installed);
	}
	/**
	 * Helper wrapper method for detectLanguage
	 *
	 * @return  string  locale or null if not found.
	 *
	 * @see     LanguageHelper::detectLanguage
	 * @since   3.4
	 */
	public function detectLanguage()
	{
		return LanguageHelper::detectLanguage();
	}
	/**
	 * Helper wrapper method for getLanguages
	 *
	 * @param   string  $key  Array key
	 *
	 * @return  array  An array of published languages.
	 *
	 * @see     LanguageHelper::getLanguages
	 * @since   3.4
	 */
	public function getLanguages($key = 'default')
	{
		return LanguageHelper::getLanguages($key);
	}
}
