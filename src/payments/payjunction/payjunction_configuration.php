<?php
/**
 * @package	HikaShop for Joomla!
 * @version	2.6.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2016 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_WOO_BOOKING_EXEC') or die('Restricted access');
?><?php
if(!function_exists('curl_init')) {
	echo '<tr><td colspan="2"><strong>The PayJunction payment plugin needs the CURL library installed but it seems that it is not available on your server. Please contact your web hosting to set it up.</strong></td></tr>';
}
hikashop_loadJslib('mootools');
?><tr>
	<td class="key">
		<label for="params[payment_params][login]">
			API Login
		</label>
	</td>
	<td>
		<input type="text" name="params[payment_params][login]" value="<?php echo @$this->element->payment_params->login; ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		<label for="params[payment_params][password]">
			API Password
		</label>
	</td>
	<td>
		<input type="text" name="params[payment_params][password]" value="<?php echo @$this->element->payment_params->password; ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		<label for="params[payment_params][domain]">
			Payment Server
		</label>
	</td>
	<td>
		<?php
		$values = array();
		$values[] = WooBookingHtml::_('select.option', 'www.payjunction.com', 'Production Server');
		$values[] = WooBookingHtml::_('select.option', 'www.payjunctionlabs.com', 'Test Server');

		echo WooBookingHtml::_('select.genericlist',   $values, "params[payment_params][domain]" , 'class="inputbox" size="1"', 'value', 'text', @$this->element->payment_params->domain ); ?>
	</td>
</tr>
<tr>
	<td class="key">
		<label for="params[payment_params][ask_ccv]">
			Ask CCV
		</label>
	</td>
	<td>
		<?php echo WooBookingHtml::_('select.booleanlist', "params[payment_params][ask_ccv]" , '',@$this->element->payment_params->ask_ccv ); ?>
	</td>
</tr>
<tr>
	<td class="key">
		<label for="params[payment_params][security]">
			Override Security
		</label>
	</td>
	<td>
		<?php echo WooBookingHtml::_('select.booleanlist', "params[payment_params][security]" , 'onclick="payjunction_avs_radio(this);" onchange="payjunction_avs_radio(this);"',@$this->element->payment_params->security ); ?>
	</td>
</tr>
</table>

<div id="payjunction_security_1" <?php
	if( !isset($this->element->payment_params->security) || !$this->element->payment_params->security ) {
		echo 'style="border: 1px solid #5c5c5c; margin: 3px; display: none;"';
	} else {
		echo 'style="border: 1px solid #5c5c5c; margin: 3px;"';
	}
?>>
<table>
<tr>
	<td class="key">
		<label for="params[payment_params][security_avs]">
			AVS Security
		</label>
	</td>
	<td>
		<?php
		$values = array();
		$values[] = WooBookingHtml::_('select.option', 'AWZ', 'Match Address OR Zip');
		$values[] = WooBookingHtml::_('select.option', 'XY', 'Match Address AND Zip');
		$values[] = WooBookingHtml::_('select.option', 'WZ', 'Match Zip');
		$values[] = WooBookingHtml::_('select.option', 'AW', 'Match Address OR 9 Digit Zip');
		$values[] = WooBookingHtml::_('select.option', 'AW', 'Match Address OR 5 Digit Zip');
		$values[] = WooBookingHtml::_('select.option', 'A', 'Match Address');
		$values[] = WooBookingHtml::_('select.option', 'X', 'Match Address AND 9 Digit Zip');
		$values[] = WooBookingHtml::_('select.option', 'Y', 'Match Address AND 5 Digit Zip');
		$values[] = WooBookingHtml::_('select.option', 'W', 'Match 9 Digit Zip');
		$values[] = WooBookingHtml::_('select.option', 'Z', 'Match 5 Digit Zip');

		echo WooBookingHtml::_('select.genericlist',   $values, "params[payment_params][security_avs]" , 'class="inputbox" size="1"', 'value', 'text', @$this->element->payment_params->security_avs ); ?>
	</td>
</tr>
<tr>
	<td class="key">
		<label for="params[payment_params][security_cvv]">
			CVV Security
		</label>
	</td>
	<td>
		<?php echo WooBookingHtml::_('select.booleanlist', "params[payment_params][security_cvv]" , '',@$this->element->payment_params->security_cvv ); ?>
	</td>
</tr>
<tr>
	<td class="key">
		<label for="params[payment_params][security_preauth]">
			PreAuth Security
		</label>
	</td>
	<td>
		<?php echo WooBookingHtml::_('select.booleanlist', "params[payment_params][security_preauth]" , '',@$this->element->payment_params->security_preauth ); ?>
	</td>
</tr>
<tr>
	<td class="key">
		<label for="params[payment_params][security_avsforce]">
			AVS Force
		</label>
	</td>
	<td>
		<?php echo WooBookingHtml::_('select.booleanlist', "params[payment_params][security_avsforce]" , '',@$this->element->payment_params->security_avsforce ); ?>
	</td>
</tr>
<tr>
	<td class="key">
		<label for="params[payment_params][security_cvvforce]">
			CVV Force
		</label>
	</td>
	<td>
		<?php echo WooBookingHtml::_('select.booleanlist', "params[payment_params][security_cvvforce]" , '',@$this->element->payment_params->security_cvvforce ); ?>
	</td>
</tr>
</table>
<script type="text/javascript">
var payjunction_el = $("payjunction_security_1");
var payjunction_s = null;
try { payjunction_s = new Fx.Slide(payjunction_el); } catch(e) {}
function payjunction_avs_radio(elem) {
	if( elem.checked ) {
		if( elem.value == "0" ) {
			if( payjunction_el.isDisplayed() ) {
				if(!payjunction_s) { payjunction_el.hide(); }
				if(payjunction_s.open) { payjunction_s.show().slideOut(); }
			}
		} else {
			if( !payjunction_el.isDisplayed() || (payjunction_s && !payjunction_s.open) ) {
				payjunction_el.show();
				if( payjunction_s ) { payjunction_s.hide().slideIn(); }
			}
		}
	}
}
window.hikashop.ready( function() {
	var e = $('params[payment_params][security]1');
	if( e.checked && !payjunction_el.isDisplayed() )
		payjunction_el.show();
});
</script>
</div>

<table>
<tr>
	<td class="key">
		<label for="params[payment_params][debug]">
			<?php echo WoobookingText::_( 'DEBUG' ); ?>
		</label>
	</td>
	<td>
		<?php echo WooBookingHtml::_('select.booleanlist', "params[payment_params][debug]" , '',@$this->element->payment_params->debug	); ?>
	</td>
</tr>
<tr>
	<td class="key">
		<label for="params[payment_params][cancel_url]">
			<?php echo WoobookingText::_( 'CANCEL_URL' ); ?>
		</label>
	</td>
	<td>
		<input type="text" name="params[payment_params][cancel_url]" value="<?php echo @$this->element->payment_params->cancel_url; ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		<label for="params[payment_params][return_url]">
			<?php echo WoobookingText::_( 'RETURN_URL' ); ?>
		</label>
	</td>
	<td>
		<input type="text" name="params[payment_params][return_url]" value="<?php echo @$this->element->payment_params->return_url; ?>" />
	</td>
</tr>
<tr>
	<td class="key">
		<label for="params[payment_params][verified_status]">
			<?php echo WoobookingText::_( 'VERIFIED_STATUS' ); ?>
		</label>
	</td>
	<td>
		<?php echo $this->data['order_statuses']->display("params[payment_params][verified_status]",@$this->element->payment_params->verified_status); ?>
	</td>
</tr>