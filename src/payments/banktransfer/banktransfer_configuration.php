<?php
/**
 * @package	HikaShop for Woobookingpro!
 * @version	2.6.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2016 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_WPBOOKINGPRO_EXEC') or die('Restricted access');
?>
<tr>
	<td class="key">
		<label for="params[payment_params][email]"><?php
			echo WoobookingText::_( 'Bank info' );
		?></label>
	</td>
	<td>
        <textarea class="form-control" name="params[payment_params][bank_info]" ><?php echo $this->escape(@$this->element->payment_params->bank_info); ?></textarea>
	</td>
</tr>

