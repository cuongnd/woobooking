<?php
/**
 * @package     woobooking.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_WPBOOKINGPRO_EXEC') or die;

FormHelper::loadFieldClass('list');

/**
 * Form Field class for the woobooking Platform.
 * Provides a list of available cache handlers
 *
 * @see    JCache
 * @since  1.7.0
 */
class FormFieldCacheHandler extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	protected $type = 'CacheHandler';

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

		// Convert to name => name array.
		foreach (JCache::getStores() as $store)
		{
			$options[] = Html::_('select.option', $store, WoobookingText::_('JLIB_FORM_VALUE_CACHE_' . $store), 'value', 'text');
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
