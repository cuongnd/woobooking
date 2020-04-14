<?php
/**
 * WooBooking! Content Management System
 *
 * Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace WooBooking\CMS\Document\Renderer\Html;

defined('_WPBOOKINGPRO_EXEC') or die;

use WooBooking\CMS\Document\DocumentRenderer;

/**
 * HTML document renderer for the component output
 *
 * @since  3.5
 */
class ComponentRenderer extends DocumentRenderer
{
	/**
	 * Renders a component script and returns the results as a string
	 *
	 * @param   string  $component  The name of the component to render
	 * @param   array   $params     Associative array of values
	 * @param   string  $content    Content script
	 *
	 * @return  string  The output of the script
	 *
	 * @since   3.5
	 */
	public function render($component = null, $params = array(), $content = null)
	{
		return $content;
	}
}
