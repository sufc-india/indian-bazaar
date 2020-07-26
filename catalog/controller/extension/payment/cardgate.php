<?php

/**
 * Opencart CardGatePlus payment extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    Payment
 * @package     Payment_CardGatePlus
 * @author      Richard Schoots, <info@cardgate.com>
 * @copyright   Copyright (c) 2016 CardGatePlus B.V. (http://www.cardgate.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ControllerExtensionPaymentCardGate extends Controller {
	var $version = '3.0.17';
	
	/**
	 * Index action
	 */
	public function _index($payment) {
		$this->load->language ( 'extension/payment/' . $payment );
		
		$data ['button_confirm'] = $this->language->get ( 'button_confirm' );
		$data ['redirect_message'] = $this->language->get ( 'text_redirect_message' );
		$data ['text_select_payment_method'] = $this->language->get ( 'text_select_payment_method' );
		$data ['text_ideal_bank_selection'] = $this->language->get ( 'text_ideal_bank_selection' );
		$data ['text_ideal_bank_alert'] = $this->language->get ( 'text_ideal_bank_alert' );
		$data ['text_ideal_bank_options'] = $this->getBankOptions ();
		
		return $this->load->view ( 'extension/payment/' . $payment, $data );
	}
	
	/**
	 * Check and register the Order and set to intialized mode
	 */
	public function _confirm($payment) {
		
		$json = array ();
		
		try {
			include 'cardgate-clientlib-php/init.php';
			$this->load->model ( 'checkout/order' );
			$this->load->model ( 'account/address' );
			
			$order_info = $this->model_checkout_order->getOrder ( $this->session->data ['order_id'] );
			
			$amount = ( int ) round ( $this->currency->format ( $order_info ['total'], $order_info ['currency_code'], false, false ) * 100, 0 );
			$currency = strtoupper ( $order_info ['currency_code'] );
			$option = substr ( $payment, 8 );
			
			$oCardGate = new cardgate\api\Client ( ( int ) $this->config->get ( 'payment_cardgate_merchant_id' ), $this->config->get ( 'payment_cardgate_api_key' ), ($this->config->get ( 'payment_cardgate_test_mode' ) == 'test' ? TRUE : FALSE) );
			$oCardGate->setIp ( $_SERVER ['REMOTE_ADDR'] );
			$oCardGate->setLanguage ( $this->language->get ( 'code' ) );
			$oCardGate->version ()->setPlatformName ( 'Opencart' );
			$oCardGate->version ()->setPlatformVersion ( VERSION );
			$oCardGate->version ()->setPluginName ( 'Opencart_CardGate' );
			$oCardGate->version ()->setPluginVersion ( $this->version );
			
			$iSiteId = ( int ) $this->config->get ( 'payment_cardgate_site_id' );
			
			$oTransaction = $oCardGate->transactions ()->create ( $iSiteId, $amount, $currency );
			
			// Configure payment option.
			$oTransaction->setPaymentMethod ( $oCardGate->methods ()->get ( $option ) );
			if ('ideal' == $option) {
				$oTransaction->setIssuer ( $_GET ['issuer_id'] );
			}
			
			// Configure customer.
			$oConsumer = $oTransaction->getConsumer ();
			$oConsumer->setEmail ( $order_info ['email'] );
			$oConsumer->setPhone( ( $order_info['telephone'] ) );
			$oConsumer->address ()->setFirstName ( $order_info ['payment_firstname'] );
			$oConsumer->address ()->setLastName ( $order_info ['payment_lastname'] );
			if (! is_null ( $order_info ['payment_address_1'] )) {
				$oConsumer->address ()->setAddress ( $order_info ['payment_address_1'] . ($order_info ['payment_address_2'] ? ' ' . $order_info ['payment_address_2'] : '') );
			}
			$oConsumer->address ()->setZipCode ( $order_info ['payment_postcode'] );
			$oConsumer->address ()->setCity ( $order_info ['payment_city'] );
			$oConsumer->address ()->setCountry ( $order_info ['payment_iso_code_2'] );
			
			if ($this->cart->hasShipping ()) {
				
				$oConsumer->shippingAddress ()->setFirstName ( $order_info ['shipping_firstname'] );
				$oConsumer->shippingAddress ()->setLastName ( $order_info ['shipping_lastname'] );
				$oConsumer->shippingAddress ()->setAddress ( $order_info ['shipping_address_1'] );
				$oConsumer->shippingAddress ()->setZipCode ( $order_info ['shipping_postcode'] );
				$oConsumer->shippingAddress ()->setCity ( $order_info ['shipping_city'] );
				$oConsumer->shippingAddress ()->setCountry ( $order_info ['shipping_iso_code_2'] );
			}
			
			$calculate = $this->config->get ( 'config_tax' );
			$products = $this->cart->getProducts ();
			$cart_item_total = 0;
			$vat_total = 0;
			$shipping_tax = 0;
			
			$oCart = $oTransaction->getCart ();
			
			foreach ( $this->cart->getProducts () as $product ) {
				$price = $this->convertAmount($this->tax->calculate ( $product ['price'], $product ['tax_class_id'], FALSE ), $order_info['currency_code']);
				$price_wt = $this->convertAmount($this->tax->calculate ( $product ['price'], $product ['tax_class_id'], TRUE ), $order_info ['currency_code']);
				$vat = $this->tax->getTax ( $price, $product ['tax_class_id'] );
				$vat_perc = round ( $vat / $price, 2 );
				$vat_per_item = round ( $price_wt - $price, 0 );
				$oItem = $oCart->addItem ( \cardgate\api\Item::TYPE_PRODUCT, $product ['model'], $product ['name'], $product ['quantity'], $price_wt );
				$oItem->setVat ( $vat_perc );
				$oItem->setVatAmount ( $vat_per_item );
				$oItem->setVatIncluded ( 1 );
				$vat_total += round ( $vat_per_item * $product ['quantity'], 0 );
				$cart_item_total += round ( $price * $product ['quantity'], 0 );
			}
			
			if ($this->cart->hasShipping () && ! empty ( $this->session->data ['shipping_method'] )) {
				$shipping_data = $this->session->data ['shipping_method'];
				if ($shipping_data ['cost'] > 0) {
					$price = $this->convertAmount( $this->tax->calculate ( $shipping_data ['cost'], $shipping_data ['tax_class_id'], FALSE ), $order_info ['currency_code']);
					$price_wt = $this->convertAmount( $this->tax->calculate ( $shipping_data ['cost'], $shipping_data ['tax_class_id'], TRUE ), $order_info ['currency_code']);
					$vat = $this->tax->getTax ( $price, $shipping_data ['tax_class_id'] );
					$vat_perc = round ( $vat / $price, 2 );
					$vat_per_item = round ( $price_wt - $price, 0 );
					$shipping_tax = $vat_per_item;
					$oItem = $oCart->addItem ( \cardgate\api\Item::TYPE_SHIPPING, $shipping_data ['code'], $shipping_data ['title'], 1, $price_wt );
					$oItem->setVat ( $vat_perc );
					$oItem->setVatAmount ( $vat_per_item );
					$oItem->setVatIncluded ( 1 );
					$vat_total += $vat_per_item;
					$cart_item_total += round ( $price * 1, 0 );
				}
			}
			
			if (isset ( $this->session->data ['voucher'] ) && $this->session->data ['voucher'] > 0) {
				$code = $this->session->data ['voucher'];
				$voucher_query = $this->db->query ( "SELECT `voucher_id`, `amount` FROM `" . DB_PREFIX . "voucher` WHERE `code` = '" . $code . "'" );
				$voucher = $voucher_query->row;
				$sku = 'voucher_id_' . $voucher ['voucher_id'];
				$price = round ( ( int ) - 1 * $voucher ['amount'] * 100, 0 );
				$oItem = $oCart->addItem ( \cardgate\api\Item::TYPE_DISCOUNT, $sku, 'gift_certificate', 1, $price );
				$oItem->setVat ( 0 );
				$oItem->setVatIncluded ( 0 );
				$cart_item_total += $price;
			}
			
			if (isset ( $this->session->data ['coupon'] ) && $this->session->data ['coupon'] > 0) {
				$order_id = ( int ) $this->session->data ['order_id'];
				$code = $this->session->data ['coupon'];
				$coupon_query = $this->db->query ( "SELECT `code`, `value`, `title` FROM `" . DB_PREFIX . "order_total` WHERE `code` = 'coupon' AND `order_id`=" . $order_id );
				$coupon = $coupon_query->row;
				$price = round ( $coupon ['value'] * 100, 0 );
				$oItem = $oCart->addItem ( \cardgate\api\Item::TYPE_DISCOUNT, $coupon ['code'], $coupon ['title'], 1, $price );
				$oItem->setVat ( 0 );
				$oItem->setVatIncluded ( 0 );
				$cart_item_total += $price;
			}
			
			// extra fees
			if (isset ( $this->session->data ['cardgatefees'] )) {
				$aFees = $this->session->data ['cardgatefees'];
				if (count ( $aFees ) > 0) {
					foreach ( $aFees as $fee ) {
						$oItem = $oCart->addItem ( \cardgate\api\Item::TYPE_HANDLING, $fee ['code'], $fee ['name'], 1, $fee ['amount'] );
						$oItem->setVatAmount ( $fee ['vat_amount'] );
						$oItem->setVatIncluded ( 0 );
						$cart_item_total += $fee ['amount'] + $fee ['vat_amount'];
					}
				}
			}
			
			$item_difference = $amount - $cart_item_total;
			
			$aTaxTotals = $this->cart->getTaxes ();
			$tax_total = 0;
			foreach ( $aTaxTotals as $total ) {
				$tax_total += $total;
			}
			
			$tax_total = $this->convertAmount($tax_total, $order_info['currency_code']);
			$tax_total += $shipping_tax;
			
			$tax_difference = $tax_total - $vat_total;
			
			if ($tax_difference != 0) {
				$item = array ();
				$price = $tax_difference;
				$oItem = $oCart->addItem ( \cardgate\api\Item::TYPE_PAYMENT, 'VAT_correction', 'correction', 1, $tax_difference );
				$oItem->setVat ( 100 );
				$oItem->setVatAmount ( $tax_difference );
				$oItem->setVatIncluded ( 1 );
			}
			$item_difference = $amount - $cart_item_total - $vat_total - $tax_difference;

			if ($item_difference != 0) {
				$item = array ();
				$price = $item_difference;
				$oItem = $oCart->addItem ( \cardgate\api\Item::TYPE_PRODUCT, 'pr_correction', 'correction', 1, $item_difference );
				$oItem->setVat ( 0 );
				$oItem->setVatAmount ( 0 );
				$oItem->setVatIncluded ( 1 );
			}
			
			$oTransaction->setCallbackUrl ( $this->url->link ( 'extension/payment/cardgategeneric/control' ) );
			$oTransaction->setSuccessUrl ( $this->url->link ( 'extension/payment/' . $payment . '/success' ) );
			$oTransaction->setFailureUrl ( $this->url->link ( 'extension/payment/' . $payment . '/cancel' ) );
			$oTransaction->setReference ( $order_info ['order_id'] );
			$oTransaction->setDescription ( str_replace ( '%id%', $order_info ['order_id'], $this->config->get ( 'payment_cardgate_order_description' ) ) );
			$oTransaction->register ();
			
			$sActionUrl = $oTransaction->getActionUrl ();
			
			if (NULL !== $sActionUrl) {
				$data ['status'] = 'success';
				$data ['redirect'] = trim ( $sActionUrl );
			} else {
				$data ['status'] = 'failed';
				$data ['error'] = 'CardGate error: ' . htmlspecialchars ( $oException_->getMessage () );
			}
		} catch ( cardgate\api\Exception $oException_ ) {
			$data ['status'] = 'failed';
			$data ['error'] = 'CardGate error: ' . htmlspecialchars ( $oException_->getMessage () );
		}
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $data ) );
	}
	
	/**
	 * After a failed transaction a customer will be send here
	 */
	public function cancel() {
		// Load the cart
		$this->response->redirect ( $this->url->link ( 'checkout/cart' ) );
	}
	
	/**
	 * After a successful transaction a customer will be send here
	 */
	public function success() {
		// Clear the cart
		$this->cart->clear ();
		$this->response->redirect ( $this->url->link ( 'checkout/success' ) );
	}
	
	/**
	 * Control URL called by gateway
	 */
	public function control() {
		$data = $_REQUEST;
		try {
			
			include 'cardgate-clientlib-php/init.php';
			$sSiteKey = $this->config->get ( 'payment_cardgate_hash_key' );
			
			$oCardGate = new cardgate\api\Client ( ( int ) $this->config->get ( 'payment_cardgate_merchant_id' ), $this->config->get ( 'payment_cardgate_api_key' ), ($this->config->get ( 'payment_cardgate_test_mode' ) == 'test' ? TRUE : FALSE) );
			$oCardGate->setIp ( $_SERVER ['REMOTE_ADDR'] );
			
			if (FALSE == $oCardGate->transactions ()->verifyCallback ( $data, $sSiteKey )) {
				$store_name = $this->config->get ( 'config_name' );
				$mail = new Mail ();
				$mail->protocol = $this->config->get ( 'config_mail_protocol' );
				$mail->parameter = $this->config->get ( 'config_mail_parameter' );
				$mail->hostname = $this->config->get ( 'config_smtp_host' );
				$mail->username = $this->config->get ( 'config_smtp_username' );
				$mail->password = $this->config->get ( 'config_smtp_password' );
				$mail->port = $this->config->get ( 'config_smtp_port' );
				$mail->timeout = $this->config->get ( 'config_smtp_timeout' );
				$mail->setTo ( $this->config->get ( 'config_email' ) );
				$mail->setFrom ( $this->config->get ( 'config_email' ) );
				$mail->setSender ( $store_name );
				$mail->setSubject ( html_entity_decode ( 'Hash check fail ' . $store_name ), ENT_QUOTES, 'UTF-8' );
				$mail->setText ( html_entity_decode ( 'A payment was not completed because of a hash check fail. Please see the details below.' . print_r ( $data, true ) . 'It could be that the amount or currency does not match for this order.', ENT_QUOTES, 'UTF-8' ) );
				$mail->send ();
				die ( 'invalid callback' );
			} else {
				$this->load->language ( 'extension/payment/cardgate' );
				$this->load->model ( 'checkout/order' );
				$order = $this->model_checkout_order->getOrder ( $data ['reference'] );
				$complete_status = $this->config->get ( 'payment_cardgate_payment_complete_status' );
				$comment = '';
				
				$waiting = false;
				if ($data ['code'] == '0' || ($data ['code'] >= '700' && $data ['code'] <= '710')) {
					$waiting = true;
					$status = $this->config->get ( 'payment_cardgate_payment_initialized_status' );
					$this->language->get ( 'text_payment_initialized' );
					switch ($data ['code']) {
						case '700' :
							$comment .= 'Transaction is waiting for user action. ';
							break;
						case '701' :
							$comment .= 'Waiting for confirmation. ';
							break;
						case '710' :
							$comment .= 'Waiting for confirmation recurring. ';
							break;
					}
				}
				
				if ($data ['code'] >= '200' && $data ['code'] < '300') {
					$status = $complete_status;
					$comment .= $this->language->get ( 'text_payment_complete' );
				}
				
				if ($data ['code'] >= '300' && $data ['code'] < '400') {
					if ($data ['code'] == '309') {
						$status = $order ['order_status_id'];
					} else {
						$status = $this->config->get ( 'payment_cardgate_payment_failed_status' );
						$comment .= $this->language->get ( 'text_payment_failed' );
					}
				}
				
				$comment .= '  ' . $this->language->get ( 'text_transaction_nr' );
				$comment .= ' ' . $data ['transaction'];
				
				if (($order ['order_status_id'] != $status && $order ['order_status_id'] != $complete_status) || ($waiting = true && $order ['order_status_id'] != $complete_status)) {
					$this->model_checkout_order->addOrderHistory ( $order ['order_id'], $status, $comment, true );
				}
				
				// Display transaction_id and status
				echo $data ['transaction'] . '.' . $data ['code'];
			}
		} catch ( cardgate\api\Exception $oException_ ) {
			echo htmlspecialchars ( $oException_->getMessage () );
		}
	}
	
	/**
	 * Fetch bank option data from cardgate
	 */
	public function getBankOptions() {
	    
	    $this->checkBankOptions();
	    $sIssuers = $this->cache->get('cardgateissuers');
	    $aIssuers = unserialize($sIssuers);
	    
		$options = '';
		foreach ( $aIssuers as $aIssuer ) {
			$options .= '<option value="' . $aIssuer ['id'] . '">' . $aIssuer ['name'] . '</option>';
		}
		return $options;
	}
	public function returnJson($message) {
		$json = array ();
		$json ['success'] = false;
		$json ['error'] = $message;
		$this->response->addHeader ( 'Content-Type: application/json' );
		$this->response->setOutput ( json_encode ( $json ) );
	}
	
	/**
	 * Check issuer refresh lifetime.
	 */
	private function checkBankOptions() {
	    
	    $iLifeTime = $this->cache->get('cardgateissuerrefresh');
	    if (!$iLifeTime || ($iLifeTime < time())){
	        $this->cacheBankOptions();
	    }
	}
	
	/**
	 * Cache bank options
	 */
	private function cacheBankOptions() {

		try {

			include 'cardgate-clientlib-php/init.php';

			$oCardGate = new cardgate\api\Client ( ( int ) $this->config->get( 'payment_cardgate_merchant_id' ), $this->config->get( 'payment_cardgate_api_key' ), ( $this->config->get( 'payment_cardgate_test_mode' ) == 'test' ? true : false ) );
			$oCardGate->setIp( $_SERVER ['REMOTE_ADDR'] );

			$aIssuers = $oCardGate->methods()->get( cardgate\api\Method::IDEAL )->getIssuers();
		} catch ( cardgate\api\Exception $oException_ ) {
			$aIssuers [0] = [
				'id'   => 0,
				'name' => htmlspecialchars( $oException_->getMessage() )
			];
		}

		$aBanks = array();

		if ( is_array( $aIssuers ) ) {
			foreach ( $aIssuers as $key => $aIssuer ) {
				$aBanks[ $aIssuer['id'] ] = $aIssuer['name'];
			}
		}

	    if (array_key_exists("INGBNL2A", $aBanks)) {
		    $iCacheTime = 24 * 60 * 60;
		    $iLifeTime = time() + $iCacheTime;
		    $this->cache->set('cardgateissuerrefresh', $iLifeTime);

		    $sIssuers = serialize( $aIssuers);
		    $this->cache->set( 'cardgateissuers', $sIssuers);
	    }
	}
	private function convertAmount($amount, $currency_code){
		return round($this->currency->format ( $amount, $currency_code, false, false ) * 100, 0 );
	}
}
