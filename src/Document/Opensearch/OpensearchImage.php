<?php
/**
 * WooBooking! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace WooBooking\CMS\Document\Opensearch;

defined('_WOO_BOOKING_EXEC') or die;

/**
 * Data object representing an OpenSearch image
 *
 * @since  1.7.0
 */
class OpensearchImage
{
	/**
	 * The images MIME type
	 *
	 * required
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	public $type = '';

	/**
	 * URL of the image or the image as base64 encoded value
	 *
	 * required
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	public $data = '';

	/**
	 * The image's width
	 *
	 * required
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	public $width;

	/**
	 * The image's height
	 *
	 * required
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	public $height;
}
