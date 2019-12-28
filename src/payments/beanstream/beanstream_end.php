<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.6.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2016 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_WOO_BOOKING_EXEC') or die('Restricted access');
?><div class="hikashop_beanstream_end" id="hikashop_beanstream_end">
	<span id="hikashop_beanstream_end_message" class="hikashop_beanstream_end_message">
		<?php echo WoobookingText::sprintf('PLEASE_WAIT_BEFORE_REDIRECTION_TO_X',$this->payment_name).'<br/>'. WoobookingText::_('CLICK_ON_BUTTON_IF_NOT_REDIRECTED');?>
	</span>
	<span id="hikashop_beanstream_end_spinner" class="hikashop_beanstream_end_spinner">
		<img src="<?php echo HIKASHOP_IMAGES.'spinner.gif';?>" />
	</span>
	<br/>
	<form id="hikashop_beanstream_form" name="hikashop_beanstream_form" action="<?php echo $this->payment_params->url;?>" method="post">
		<div id="hikashop_beanstream_end_image" class="hikashop_beanstream_end_image">
			<input id="hikashop_beanstream_button" class="btn btn-primary" type="submit" value="<?php echo WoobookingText::_('PAY_NOW');?>" name="" alt="<?php echo WoobookingText::_('PAY_NOW');?>" />
		</div>
		<?php
			foreach( $this->vars as $name => $value ) {
				echo '<input type="hidden" name="'.$name.'" value="'.htmlspecialchars((string)$value).'" />';
			}
			$doc = JFactory::getDocument();
			$doc->addScriptDeclaration("window.hikashop.ready( function() {document.getElementById('hikashop_beanstream_form').submit();});");
			JRequest::setVar('noform',1);
		?>
	</form>
</div>