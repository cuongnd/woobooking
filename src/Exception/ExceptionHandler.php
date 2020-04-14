<?php
/**
 * Woobookingpro! Content Management System
 *
 * Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace WooBooking\CMS\Exception;
use WooBooking\CMS\Log\Log;
defined('_WPBOOKINGPRO_EXEC') or die;

/**
 * Displays the custom error page when an uncaught exception occurs.
 *
 * @since  3.0
 */
class ExceptionHandler
{
	/**
	 * Render the error page based on an exception.
	 *
	 * @param   \Exception|\Throwable  $error  An Exception or Throwable (PHP 7+) object for which to render the error page.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function render($error)
	{
		$expectedClass = PHP_MAJOR_VERSION >= 7 ? '\Throwable' : '\Exception';
		$isException   = $error instanceof $expectedClass;

		// In PHP 5, the $error object should be an instance of \Exception; PHP 7 should be a Throwable implementation
		if ($isException)
		{
			try
			{
				// Try to log the error, but don't let the logging cause a fatal error
				try
				{
					Log::add(
						sprintf(
							'Uncaught %1$s of type %2$s thrown. Stack trace: %3$s',
							$expectedClass,
							get_class($error),
							$error->getTraceAsString()
						),
						Log::CRITICAL,
						'error'
					);
				}
				catch (\Throwable $e)
				{
					// Logging failed, don't make a stink about it though
				}
				catch (\Exception $e)
				{
					// Logging failed, don't make a stink about it though
				}

				$app = \Factory::getApplication();

				// If site is offline and it's a 404 error, just go to index (to see offline message, instead of 404)
				if ($error->getCode() == '404' && $app->get('offline') == 1)
				{
					$app->redirect('index.php');
				}

				$attributes = array(
					'charset'   => 'utf-8',
					'lineend'   => 'unix',
					'tab'       => "\t",
					'language'  => 'en-GB',
					'direction' => 'ltr',
				);

				// If there is a \JLanguage instance in \Factory then let's pull the language and direction from its metadata
				if (\Factory::$language)
				{
					$attributes['language']  = \Factory::getLanguage()->getTag();
					$attributes['direction'] = \Factory::getLanguage()->isRtl() ? 'rtl' : 'ltr';
				}

				$document = \Document::getInstance('error', $attributes);

				if (!$document)
				{
					// We're probably in an CLI environment
					jexit($error->getMessage());
				}

                //TODO chưa có template error
				// Get the current template from the application
				//$template = $app->getTemplate();
                echo "<pre>";
                print_r($error, false);
                echo "</pre>";
                die;

                // Push the error object into the document
				$document->setError($error);

				if (ob_get_contents())
				{
					ob_end_clean();
				}

				$document->setTitle(\JText::_('ERROR') . ': ' . $error->getCode());

				$data = $document->render(
					false,
					array(
						'directory' => JPATH_THEMES,
						'debug'     => WPBOOKING_PRO_DEBUG,
					)
				);


                // Do not allow cache
				$app->allowCache(false);

				// If nothing was rendered, just use the message from the Exception
				if (empty($data))
				{
					$data = $error->getMessage();
				}

				$app->setBody($data);

				echo ($app->toString());

				$app->close(0);

				// This return is needed to ensure the test suite does not trigger the non-Exception handling below
				return;
			}
			catch (\Throwable $e)
			{
				// Pass the error down
			}
			catch (\Exception $e)
			{
				// Pass the error down
			}
		}

		// This isn't an Exception, we can't handle it.
		if (!headers_sent())
		{
			header('HTTP/1.1 500 Internal Server Error');
		}

		$message = 'Error';

		if ($isException)
		{
			// Make sure we do not display sensitive data in production environments
			if (ini_get('display_errors'))
			{
				$message .= ': ';

				if (isset($e))
				{
					$message .= $e->getMessage() . ': ';
				}

				$message .= $error->getMessage();
			}
		}

		echo ($message);

		jexit(1);
	}
}
