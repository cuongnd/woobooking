<?php

use WooBooking\CMS\Utilities\Utility;

class WBPayment
{
    private static $instance;
    var $type = 'payment';
    var $debug = false;
    var $object_payment = null;
    var $accepted_currencies = array();
    var $doc_form = 'generic';
    var $payment_params;
    var $features = array(
        'authorize_capture' => false,
        'recurring' => false,
        'refund' => false
    );
    protected $currency;
    /**
     * @var int
     */
    private $currency_id;

    function __construct($config) {
        $this->payment_params=$config->params->get('payment_params');
        $this->object_payment=$config;
        $this->debug=$config->debug;
    }
    public static function getInstance($type,$config){
        $app=Factory::getApplication();
        if (is_object(self::$instance[$type]))
        {
            return self::$instance[$type];
        }
        
        $file_payment_path=__DIR__."/$type/".$type.".php";

        $file_short_payment_path=Utility::get_short_file_by_path($file_payment_path);
        $response=new stdClass();
        if(file_exists($file_payment_path)){
            require_once $file_payment_path;
            $class_name="WBPayment".ucfirst($type);
            if(!class_exists($class_name)){
                $app->enqueueMessage("file $file_short_payment_path must has class $class_name, please check it");
            }else{
                self::$instance[$type]=new $class_name($config);
            }
        }else{
            $app->enqueueMessage("cannot find file controller $file_short_payment_path not exists, please create it first");
        }

        return self::$instance[$type];
    }
    function onPaymentDisplay(&$order, &$methods, &$usable_methods)
    {
        $app = JFactory::getApplication();
        if (empty($methods) || empty($this->name))
            return true;
        $currencyClass = hikashop_get('class.currency');
        if (!empty($order->total)) {
            $null = null;
            $currency_id = intval($order->total->prices[0]->price_currency_id);
            $currency = $currencyClass->getCurrencies($currency_id, $null);
            if (!empty($currency) && !empty($this->accepted_currencies) && !in_array(@$currency[$currency_id]->currency_code, $this->accepted_currencies))
                return true;
            $this->currency = $currency;
            $this->currency_id = $currency_id;
        }
        $this->currencyClass = $currencyClass;
        $shippingClass = hikashop_get('class.shipping');
        $volumeHelper = hikashop_get('helper.volume');
        $weightHelper = hikashop_get('helper.weight');
        $zone_id = hikashop_getZone('payment');
        foreach ($methods as $method) {
            if ($method->payment_type != $this->name || !$method->enabled || !$method->payment_published)
                continue;
            $payment_deposit_level_selected = $app->input->getString("payment_deposit_level_$method->payment_id");
            if ($method->payment_params->payment_deposit == "1" && $payment_deposit_level_selected === "partial_payment") {
                $payment_deposit_level_selected = $app->input->getString("payment_deposit_level_$method->payment_id");
                $method->payment_deposit_level_selected = $payment_deposit_level_selected;
                $payment_deposit_type = $method->payment_params->payment_deposit_type;
                if ($payment_deposit_type == "amount") {
                    $method->payment_deposit_value = round($method->payment_params->payment_deposit_amount, -3);
                } else {
                    $payment_deposit_percent = $method->payment_params->payment_deposit_percent;
                    $method->payment_deposit_value = round(($payment_deposit_percent * $order->full_total->prices[0]->price_value_with_tax) / 100, -3);
                }
            }
            if (method_exists($this, 'needCC')) {
                $this->needCC($method);
            } else if (!empty($this->ask_cc)) {
                $method->ask_cc = true;
                if (!empty($this->ask_owner))
                    $method->ask_owner = true;
                if (!empty($method->payment_params->ask_ccv))
                    $method->ask_ccv = true;
            }
            $price = null;
            if (@$method->payment_params->payment_price_use_tax) {
                if (isset($order->order_full_price))
                    $price = $order->order_full_price;
                if (isset($order->total->prices[0]->price_value_with_tax))
                    $price = $order->total->prices[0]->price_value_with_tax;
                if (isset($order->full_total->prices[0]->price_value_with_tax))
                    $price = $order->full_total->prices[0]->price_value_with_tax;
                if (isset($order->full_total->prices[0]->price_value_without_payment_with_tax))
                    $price = $order->full_total->prices[0]->price_value_without_payment_with_tax;
            } else {
                if (isset($order->order_full_price))
                    $price = $order->order_full_price;
                if (isset($order->total->prices[0]->price_value))
                    $price = $order->total->prices[0]->price_value;
                if (isset($order->full_total->prices[0]->price_value))
                    $price = $order->full_total->prices[0]->price_value;
                if (isset($order->full_total->prices[0]->price_value_without_payment))
                    $price = $order->full_total->prices[0]->price_value_without_payment;
            }
            if (!empty($method->payment_params->payment_min_price) && hikashop_toFloat($method->payment_params->payment_min_price) > $price) {
                $method->errors['min_price'] = (hikashop_toFloat($method->payment_params->payment_min_price) - $price);
                continue;
            }
            if (!empty($method->payment_params->payment_max_price) && hikashop_toFloat($method->payment_params->payment_max_price) < $price) {
                $method->errors['max_price'] = ($price - hikashop_toFloat($method->payment_params->payment_max_price));
                continue;
            }
            if (!empty($method->payment_params->payment_max_volume) && bccomp((float)@$method->payment_params->payment_max_volume, 0, 3)) {
                $method->payment_params->payment_max_volume_orig = $method->payment_params->payment_max_volume;
                $method->payment_params->payment_max_volume = $volumeHelper->convert($method->payment_params->payment_max_volume, @$method->payment_params->payment_size_unit);
                if ($method->payment_params->payment_max_volume < $order->volume) {
                    $method->errors['max_volume'] = ($method->payment_params->payment_max_volume - $order->volume);
                    continue;
                }
            }
            if (!empty($method->payment_params->payment_min_volume) && bccomp((float)@$method->payment_params->payment_min_volume, 0, 3)) {
                $method->payment_params->payment_min_volume_orig = $method->payment_params->payment_min_volume;
                $method->payment_params->payment_min_volume = $volumeHelper->convert($method->payment_params->payment_min_volume, @$method->payment_params->payment_size_unit);
                if ($method->payment_params->payment_min_volume > $order->volume) {
                    $method->errors['min_volume'] = ($order->volume - $method->payment_params->payment_min_volume);
                    continue;
                }
            }
            if (!empty($method->payment_params->payment_max_weight) && bccomp((float)@$method->payment_params->payment_max_weight, 0, 3)) {
                $method->payment_params->payment_max_weight_orig = $method->payment_params->payment_max_weight;
                $method->payment_params->payment_max_weight = $weightHelper->convert($method->payment_params->payment_max_weight, @$method->payment_params->payment_weight_unit);
                if ($method->payment_params->payment_max_weight < $order->weight) {
                    $method->errors['max_weight'] = ($method->payment_params->payment_max_weight - $order->weight);
                    continue;
                }
            }
            if (!empty($method->payment_params->payment_min_weight) && bccomp((float)@$method->payment_params->payment_min_weight, 0, 3)) {
                $method->payment_params->payment_min_weight_orig = $method->payment_params->payment_min_weight;
                $method->payment_params->payment_min_weight = $weightHelper->convert($method->payment_params->payment_min_weight, @$method->payment_params->payment_weight_unit);
                if ($method->payment_params->payment_min_weight > $order->weight) {
                    $method->errors['min_weight'] = ($order->weight - $method->payment_params->payment_min_weight);
                    continue;
                }
            }
            if (!empty($method->payment_params->payment_max_quantity) && (int)$method->payment_params->payment_max_quantity) {
                if ($method->payment_params->payment_max_quantity < $order->total_quantity) {
                    $method->errors['max_quantity'] = ($method->payment_params->payment_max_quantity - $order->total_quantity);
                    continue;
                }
            }
            if (!empty($method->payment_params->payment_min_quantity) && (int)$method->payment_params->payment_min_quantity) {
                if ($method->payment_params->payment_min_quantity > $order->total_quantity) {
                    $method->errors['min_quantity'] = ($order->total_quantity - $method->payment_params->payment_min_quantity);
                    continue;
                }
            }
            $method->features = $this->features;
            if (!$this->checkPaymentDisplay($method, $order))
                continue;
            if (!empty($order->paymentOptions) && !empty($order->paymentOptions['recurring']) && empty($method->features['recurring']))
                continue;
            if (!empty($order->paymentOptions) && !empty($order->paymentOptions['term']) && empty($method->features['authorize_capture']))
                continue;
            if (!empty($order->paymentOptions) && !empty($order->paymentOptions['refund']) && empty($method->features['refund']))
                continue;
            if ((int)$method->payment_ordering > 0 && !isset($usable_methods[(int)$method->payment_ordering]))
                $usable_methods[(int)$method->payment_ordering] = $method;
            else
                $usable_methods[] = $method;

        }
        return true;
    }

    function onPaymentSave(&$cart, &$rates, &$payment_id)
    {
        $usable = array();
        $this->onPaymentDisplay($cart, $rates, $usable);
        $payment_id = (int)$payment_id;
        foreach ($usable as $usable_method) {
            if ($usable_method->payment_id == $payment_id)
                return $usable_method;
        }
        return false;
    }

    function onPaymentConfiguration(&$element)
    {
        $this->pluginConfiguration($element);
        if (empty($element) || empty($element->payment_type)) {
            $element = new stdClass();
            $element->payment_type = $this->pluginName;
            $element->payment_params = new stdClass();
            $this->getPaymentDefaultValues($element);
        }
        $this->order_statuses = hikashop_get('type.categorysub');
        $this->order_statuses->type = 'status';
        $this->currency = hikashop_get('type.currency');
        $this->weight = hikashop_get('type.weight');
        $this->volume = hikashop_get('type.volume');
    }

    function onPaymentConfigurationSave(&$element)
    {
        if (empty($this->pluginConfig))
            return true;
        $formData = JRequest::getVar('data', array(), '', 'array', JREQUEST_ALLOWRAW);
        if (!isset($formData['payment']['payment_params']))
            return true;
        foreach ($this->pluginConfig as $key => $config) {
            if ($config[1] == 'textarea' || $config[1] == 'big-textarea') {
                $element->payment_params->$key = @$formData['payment']['payment_params'][$key];
            }
        }
        return true;
    }

    function onBeforeOrderCreate(&$order, &$do)
    {
        $app = JFactory::getApplication();
        if ($app->isAdmin())
            return true;
        if (empty($order->order_payment_method) || $order->order_payment_method != $this->name)
            return true;
        if (!empty($order->order_type) && $order->order_type != 'sale')
            return true;
        $this->loadOrderData($order);
        $this->loadPaymentParams($order);
        if (empty($this->payment_params)) {
            $do = false;
            return true;
        }
    }
    public function showPage($page){
        $name=$this->name;
       require __DIR__."/$name/{$name}_end.php";

    }
    function onAfterOrderConfirm(&$order, &$methods, $method_id)
    {
        $this->payment = $methods[$method_id];
        $this->payment_params =& $this->payment->payment_params;
        $this->payment_name = $this->payment->payment_name;
        $this->loadOrderData($order);
        $this->order = $order;
    }

    function onPaymentNotification(&$statuses)
    {
    }

    function onOrderPaymentCapture(&$order, $total)
    {
        return false;
    }

    function onOrderAuthorizationCancel(&$order)
    {
        return false;
    }

    function onOrderAuthorizationRenew(&$order)
    {
        return false;
    }

    function onOrderPaymentRefund(&$order, $total)
    {
        return false;
    }

    function getOrder($order_id)
    {
        $ret = null;
        if (empty($order_id))
            return $ret;
        $orderClass = hikashop_get('class.order');
        $ret = $orderClass->get($order_id);
        return $ret;
    }



    function loadPaymentParams(&$order)
    {
        $payment_id = $order->order_payment_id;
        $this->payment_params = null;
        if (!empty($order->order_payment_method) && $order->order_payment_method == $this->name && !empty($payment_id) && $this->pluginParams($payment_id))
            $this->payment_params =& $this->plugin_params;
    }






    function getPaymentDefaultValues(&$element)
    {
    }

    function checkPaymentDisplay(&$method, &$order)
    {
        return true;
    }
}