<?php
/**
 * @package     woobooking.Platform
 * @subpackage  Form
 *
 *  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_WPBOOKINGPRO_EXEC') or die;

FormHelper::loadFieldClass('list');

/**
 * Form Field class for the woobooking Platform.
 * Provides a select list of session handler options.
 *
 * @since  1.7.0
 */
class WpBookingPro_FormFieldSessionHandler extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	protected $type = 'SessionHandler';

	/**
	 * Method to get the session handler field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.7.0
	 */
	protected function getOptions()
	{
		$options = array();

		// Get the options from JSession.
		foreach (JSession::getStores() as $store)
		{
			$options[] = Html::_('select.option', $store, WoobookingText::_('JLIB_FORM_VALUE_SESSION_' . $store), 'value', 'text');
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
