<?php
/**
 * @package     woobooking.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_WOO_BOOKING_EXEC') or die;

/**
 * Form Field class for the woobooking Platform.
 * Supports a nested checkbox field listing user groups.
 * Multiselect is available by default.
 *
 * @since       1.7.0
 * @deprecated  3.5
 */
class FormFieldUsergroup extends FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	protected $type = 'Usergroup';

	/**
	 * Method to get the user group field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.7.0
	 */
	protected function getInput()
	{
		Log::add('JFormFieldUsergroup is deprecated. Use JFormFieldUserGroupList instead.', Log::WARNING, 'deprecated');

		$options = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= $this->disabled ? ' disabled' : '';
		$attr .= $this->size ? ' size="' . $this->size . '"' : '';
		$attr .= $this->multiple ? ' multiple' : '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= $this->autofocus ? ' autofocus' : '';

		// Initialize JavaScript field attributes.
		$attr .= !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';
		$attr .= !empty($this->onclick) ? ' onclick="' . $this->onclick . '"' : '';

		// Iterate through the children and build an array of options.
		foreach ($this->element->children() as $option)
		{
			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}

			$disabled = (string) $option['disabled'];
			$disabled = ($disabled == 'true' || $disabled == 'disabled' || $disabled == '1');

			// Create a new option object based on the <option /> element.
			$tmp = Html::_(
				'select.option', (string) $option['value'], trim((string) $option), 'value', 'text',
				$disabled
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		return Html::_('access.usergroup', $this->name, $this->value, $attr, $options, $this->id);
	}
}
