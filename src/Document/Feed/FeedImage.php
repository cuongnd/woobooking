<?php
/**
 * WooBooking! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace WooBooking\CMS\Document\Feed;

defined('_WOO_BOOKING_EXEC') or die;

/**
 * Data object representing a feed image
 *
 * @since  1.7.0
 */
class FeedImage
{
	/**
	 * Title image attribute
	 *
	 * required
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	public $title = '';

	/**
	 * URL image attribute
	 *
	 * required
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	public $url = '';

	/**
	 * Link image attribute
	 *
	 * required
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	public $link = '';

	/**
	 * Width image attribute
	 *
	 * optional
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	public $width;

	/**
	 * Title feed attribute
	 *
	 * optional
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	public $height;

	/**
	 * Title feed attribute
	 *
	 * optional
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	public $description;
}
