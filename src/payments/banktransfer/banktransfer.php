<?php
/**
 * @package	HikaShop for Woobookingpro!
 * @version	2.6.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2016 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_WPBOOKINGPRO_EXEC') or die('Restricted access');
?><?php
class WBPaymentBanktransfer extends WBPayment {
	var $name = 'banktransfer';
	var $multiple = true;
	var $pluginConfig = array(
		'order_status' => array('ORDER_STATUS', 'orderstatus', 'verified'),
		'status_notif_email' => array('ORDER_STATUS_NOTIFICATION', 'boolean','0'),
		'information' => array('BANK_ACCOUNT_INFORMATION', 'editor'),
		'return_url' => array('RETURN_URL', 'input'),
	);

	function onAfterOrderConfirm(&$order,&$methods=null,$method_id=0) {
        if(!isset($this->payment_params)){
            echo "please config payment width bank account";
            return false;
        }
		$this->bank_info =& $this->payment_params->bank_info;
		return $this->showPage('end');

	}

	function getPaymentDefaultValues(&$element) {
		$element->payment_name='Bank transfer';
		$element->payment_description='You can pay by sending us a bank transfer.';
		$element->payment_images='Bank_transfer';

		$element->payment_params->information='Account owner: XXXXX<br/>
<br/>
Owner address:<br/>
<br/>
XX XXXX XXXXXX<br/>
<br/>
XXXXX XXXXXXXX<br/>
<br/>
IBAN International Bank Account Number:<br/>
<br/>
XXXX XXXX XXXX XXXX XXXX XXXX XXX<br/>
<br/>
BIC swift Bank Identification Code:<br/>
<br/>
XXXXXXXXXXXXXX<br/>
<br/>
Bank name: XXXXXXXXXXX<br/>
<br/>
Bank address:<br/>
<br/>
XX XXXX XXXXXX<br/>
<br/>
XXXXX XXXXXXXX';
		$element->payment_params->order_status='created';
	}
}
