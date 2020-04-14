<?php
/**
 * woobooking! Content Management System
 *
 * Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace WooBooking\CMS\Form\Field;

defined('_WPBOOKINGPRO_EXEC') or die;

use WooBooking\CMS\Form\FormHelper;

FormHelper::loadFieldClass('predefinedlist');

/**
 * Field to load a list of available users statuses
 *
 * @since  3.2
 */
class UserstateField extends FormFieldPredefinedList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   3.2
	 */
	protected $type = 'UserState';

	/**
	 * Available statuses
	 *
	 * @var  array
	 * @since  3.2
	 */
	protected $predefinedOptions = array(
		'0'  => 'JENABLED',
		'1'  => 'JDISABLED',
	);
}
