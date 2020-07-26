<?php
class ModelExtensionTotalCardgatefee extends Model {
	public function getTotal($total) {
		$this->language->load ( 'extension/total/cardgatefee' );
		
		$shipping_method = isset ( $this->session->data ['shipping_method'] ['code'] ) ? $this->session->data ['shipping_method'] ['code'] : '';
		$payment_method = isset ( $this->session->data ['payment_method'] ['code'] ) ? $this->session->data ['payment_method'] ['code'] : '';
		
		if (isset ( $this->session->data ['default'] ['shipping_method'] ['code'] ))
			$shipping_method = $this->session->data ['default'] ['shipping_method'] ['code'];
		if (isset ( $this->session->data ['default'] ['payment_method'] ['code'] ))
			$payment_method = $this->session->data ['default'] ['payment_method'] ['code'];
		
		$order_info = '';
		if (isset ( $this->session->data ['order_id'] )) {
			$this->load->model ( 'checkout/order' );
			$order_info = $this->model_checkout_order->getOrder ( $this->session->data ['order_id'] );
		}
		
		if (isset ( $this->request->get ['order_id'] )) {
			$this->load->model ( 'checkout/order' );
			$order_info = $this->model_checkout_order->getOrder ( $this->request->get ['order_id'] );
		}
		
		if ($order_info) {
			$currency_code = $order_info ['currency_code'];
			
			if (! $shipping_method) {
				$shipping_method = $order_info ['shipping_code'];
			}
			
			if (! $payment_method) {
				$payment_method = $order_info ['payment_code'];
			}
		}
		
		/* For manual order insertion */
		if (isset ( $_POST ['payment_code'] ) && ! empty ( $_POST ['payment_code'] ))
			$payment_method = $_POST ['payment_code'];
		if (isset ( $_POST ['shipping_code'] ) && ! empty ( $_POST ['shipping_code'] ))
			$shipping_method = $_POST ['shipping_code'];
		
		$address = array ();
		if (isset ( $this->session->data ['shipping_address'] ))
			$address = $this->session->data ['shipping_address'];
		
		if ($this->cart->getSubTotal ()) {
			
			unset ( $this->session->data ['cardgatefees'] );
			
			for($i = 1; $i <= 12; $i ++) {
				$cardgatefee_total = ( float ) $this->config->get ( 'total_cardgatefee_total' . $i );
				if (empty ( $cardgatefee_total ))
					$cardgatefee_total = 0;
				
				$cardgatefee_total_max = ( float ) $this->config->get ( 'total_cardgatefee_total_max' . $i );
				
				if ($this->config->get ( 'total_cardgatefee_status' . $i ) != 1)
					continue;
				if (! $this->config->get ( 'total_cardgatefee_name' . $i ))
					continue;
				if ($cardgatefee_total > $this->cart->getSubTotal ())
					continue;
				if ($cardgatefee_total_max && $cardgatefee_total_max < $this->cart->getSubTotal ())
					continue;
				
				if ($this->config->get ( 'total_cardgatefee_payment' . $i ) && $this->config->get ( 'total_cardgatefee_payment' . $i ) != $payment_method)
					continue;
				if ($this->config->get ( 'total_cardgatefee_shipping' . $i ) && $this->config->get ( 'total_cardgatefee_shipping' . $i ) . '.' . $this->config->get ( 'total_cardgatefee_shipping' . $i ) != $shipping_method && $this->config->get ( 'total_cardgatefee_shipping' . $i ) != $shipping_method)
					continue;
				
				if ($this->config->get ( 'total_cardgatefee_geo_zone_id' . $i ) && $address) {
					
					$query = $this->db->query ( "SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id='" . ( int ) $this->config->get ( 'total_cardgatefee_geo_zone_id' . $i ) . "' AND country_id = '" . ( int ) $address ['country_id'] . "' AND (zone_id = '" . ( int ) $address ['zone_id'] . "' OR zone_id = '0')" );
					if ($query->num_rows == 0)
						continue;
				}
				
				$tax_vat = 0;
				
				if ($this->config->get('total_cardgatefee_cost' .$i)){
				    $total_fee = (float)$this->config->get('total_cardgatefee_cost' .$i);
				} else {
				    $total_fee = 0;
				}
				
				if ($this->config->get('total_cardgatefee_cost_percentage' . $i)){
				    $total_percentage_fee = (float) $this->cart->getSubTotal() * ($this->config->get('total_cardgatefee_cost_percentage' . $i)/100);
				} else {
				    $total_percentage_fee = 0;
				}
				
				$total_cardgatefee_cost = $total_fee + $total_percentage_fee;
				
				if ($this->config->get ( 'total_cardgatefee_tax_class_id' . $i )) {
					$tax_rates = $this->tax->getRates ( $total_cardgatefee_cost, $this->config->get ( 'total_cardgatefee_tax_class_id' . $i ) );
					
					foreach ( $tax_rates as $tax_rate ) {
						if (! isset ( $total ['taxes'] [$tax_rate ['tax_rate_id']] )) {
							$total ['taxes'] [$tax_rate ['tax_rate_id']] = $tax_rate ['amount'];
							$tax_vat += $tax_rate ['amount'];
						} else {
							$total ['taxes'] [$tax_rate ['tax_rate_id']] += $tax_rate ['amount'];
							$tax_vat += $tax_rate ['amount'];
						}
					}
				}
				
				$this->session->data ['cardgatefees'] [$i] ['code'] = 'total_cardgatefee';
				$this->session->data ['cardgatefees'] [$i] ['name'] = $this->config->get ( 'total_cardgatefee_name' . $i );
				$this->session->data ['cardgatefees'] [$i] ['amount'] = round($total_cardgatefee_cost ,2);
				$this->session->data ['cardgatefees'] [$i] ['vat_amount'] = round ( $tax_vat * 100 );
				
				$total ['totals'] [] = array (
						'code' => 'cardgatefee',
						'title' => $this->config->get ( 'total_cardgatefee_name' . $i ),
				        'value' => round ( $total_cardgatefee_cost, 2),
						'sort_order' => $this->config->get ( 'total_cardgatefee_sort_order' . $i ) 
				);
				
				$total ['total'] += round ( $total_cardgatefee_cost, 2);
			}
		}
	}
}

?>