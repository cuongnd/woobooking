<?php
/**
 * @package     woobooking.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
namespace WooBooking\CMS\Form\fields;
defined('_WPBOOKINGPRO_EXEC') or die;
use WooBooking\CMS\Form\fields\FormFieldList;
use WooBooking\CMS\Form\FormField;
use WooBooking\CMS\Form\FormHelper;
use WooBooking\CMS\Html\Html;
use WoobookingText;
FormHelper::loadFieldClass('list');

/**
 * Form Field class for the woobooking Platform.
 * Provides a select list of integers with specified first, last and step values.
 *
 * @since  1.7.0
 */
class WpBookingPro_FormFieldInteger extends FormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	protected $type = 'Integer';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.7.0
	 */
	protected function getOptions()
	{
		$options = array();

		// Initialize some field attributes.
		$first = (int) $this->element['first'];
		$last = (int) $this->element['last'];
		$step = (int) $this->element['step'];

		// Sanity checks.
		if ($step == 0)
		{
			// Step of 0 will create an endless loop.
			return $options;
		}
		elseif ($first < $last && $step < 0)
		{
			// A negative step will never reach the last number.
			return $options;
		}
		elseif ($first > $last && $step > 0)
		{
			// A position step will never reach the last number.
			return $options;
		}
		elseif ($step < 0)
		{
			// Build the options array backwards.
			for ($i = $first; $i >= $last; $i += $step)
			{
				$options[] = Html::_('select.option', $i);
			}
		}
		else
		{
			// Build the options array.
			for ($i = $first; $i <= $last; $i += $step)
			{
				$options[] = Html::_('select.option', $i);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
