<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.6.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2016 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_WOO_BOOKING_EXEC') or die('Restricted access');
?><div class="hikashop_eway_thankyou" id="hikashop_eway_thankyou">
	<span id="hikashop_eway_thankyou_message" class="hikashop_eway_thankyou_message">
		<?php echo WoobookingText::_('THANK_YOU_FOR_PURCHASE');
		if(!empty($this->return_url)){
			echo '<br/><a href="'.$this->return_url.'">'.WoobookingText::_('GO_BACK_TO_SHOP').'</a>';
		}?>
	</span>
</div>
<?php
if(!empty($this->return_url)){
	$doc =& JFactory::getDocument();
	$doc->addScriptDeclaration("window.hikashop.ready( function() {window.location='".$this->return_url."'});");
}
