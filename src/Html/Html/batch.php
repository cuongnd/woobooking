<?php
/**
 * @package     WooBooking.Libraries
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_WOO_BOOKING_EXEC') or die;

/**
 * Extended Utility class for batch processing widgets.
 *
 * @since       1.7
 *
 * @deprecated  4.0 Use JLayout directly
 */
abstract class HtmlBatch
{
	/**
	 * Display a batch widget for the access level selector.
	 *
	 * @return  string  The necessary HTML for the widget.
	 *
	 * @since       1.7
	 *
	 * @deprecated  4.0 instead of Html::_('batch.access'); use JLayoutHelper::render('WooBooking.html.batch.access', array());
	 */
	public static function access()
	{
		Log::add('The use of Html::_("batch.access") is deprecated use JLayout instead.', Log::WARNING, 'deprecated');

		return JLayoutHelper::render('WooBooking.html.batch.access', array());
	}

	/**
	 * Displays a batch widget for moving or copying items.
	 *
	 * @param   string  $extension  The extension that owns the category.
	 *
	 * @return  string  The necessary HTML for the widget.
	 *
	 * @since       1.7
	 *
	 * @deprecated  4.0 instead of Html::_('batch.item'); use JLayoutHelper::render('WooBooking.html.batch.item', array('extension' => 'com_XXX'));
	 */
	public static function item($extension)
	{
		$displayData = array('extension' => $extension);

		Log::add('The use of Html::_("batch.item") is deprecated use JLayout instead.', Log::WARNING, 'deprecated');

		return JLayoutHelper::render('WooBooking.html.batch.item', $displayData);
	}

	/**
	 * Display a batch widget for the language selector.
	 *
	 * @return  string  The necessary HTML for the widget.
	 *
	 * @since       2.5
	 *
	 * @deprecated  4.0 instead of Html::_('batch.language'); use JLayoutHelper::render('WooBooking.html.batch.language', array());
	 */
	public static function language()
	{
		Log::add('The use of Html::_("batch.language") is deprecated use JLayout instead.', Log::WARNING, 'deprecated');

		return JLayoutHelper::render('WooBooking.html.batch.language', array());
	}

	/**
	 * Display a batch widget for the user selector.
	 *
	 * @param   boolean  $noUser  Choose to display a "no user" option
	 *
	 * @return  string  The necessary HTML for the widget.
	 *
	 * @since       2.5
	 *
	 * @deprecated  4.0 instead of Html::_('batch.user'); use JLayoutHelper::render('WooBooking.html.batch.user', array());
	 */
	public static function user($noUser = true)
	{
		$displayData = array('noUser' => $noUser);

		Log::add('The use of Html::_("batch.user") is deprecated use JLayout instead.', Log::WARNING, 'deprecated');

		return JLayoutHelper::render('WooBooking.html.batch.user', $displayData);
	}

	/**
	 * Display a batch widget for the tag selector.
	 *
	 * @return  string  The necessary HTML for the widget.
	 *
	 * @since       3.1
	 *
	 * @deprecated  4.0 instead of Html::_('batch.tag'); use JLayoutHelper::render('WooBooking.html.batch.tag', array());
	 */
	public static function tag()
	{
		Log::add('The use of Html::_("batch.tag") is deprecated use JLayout instead.', Log::WARNING, 'deprecated');

		return JLayoutHelper::render('WooBooking.html.batch.tag', array());
	}
}
