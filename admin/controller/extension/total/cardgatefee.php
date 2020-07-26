<?php
class ControllerExtensionTotalCardgatefee extends Controller {
	private $error = array ();
	public function index() {
		$this->language->load ( 'extension/total/cardgatefee' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'setting/setting' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate ()) {
			$this->model_setting_setting->editSetting ( 'total_cardgatefee', $this->request->post );
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$this->response->redirect ( $this->url->link ( 'marketplace/extension', 'user_token=' . $this->session->data ['user_token'] . '&type=total', true ) );
		}
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		$data ['text_none'] = $this->language->get ( 'text_none' );
		
		$data ['entry_name'] = $this->language->get ( 'entry_name' );
		$data ['entry_cost_percentage'] = $this->language->get ( 'entry_cost_percentage' );
		$data ['entry_cost'] = $this->language->get ( 'entry_cost' );
		$data ['entry_geo_zone'] = $this->language->get ( 'entry_geo_zone' );
		$data ['entry_tax'] = $this->language->get ( 'entry_tax' );
		$data ['text_edit'] = $this->language->get ( 'text_edit' );
		
		$data ['tab_fee'] = $this->language->get ( 'tab_fee' );
		$data ['tab_general'] = $this->language->get ( 'tab_general' );
		$data ['text_all'] = $this->language->get ( 'text_all' );
		$data ['entry_order_total'] = $this->language->get ( 'entry_order_total' );
		
		$data ['entry_payment'] = $this->language->get ( 'entry_payment' );
		$data ['entry_shipping'] = $this->language->get ( 'entry_shipping' );
		$data ['entry_status'] = $this->language->get ( 'entry_status' );
		$data ['entry_sort_order'] = $this->language->get ( 'entry_sort_order' );
		$data ['entry_order_max_total'] = $this->language->get ( 'entry_order_max_total' );
		
		$data ['button_save'] = $this->language->get ( 'button_save' );
		$data ['button_cancel'] = $this->language->get ( 'button_cancel' );
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = '';
		}
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/dashboard', 'user_token=' . $this->session->data ['user_token'], true ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_extension' ),
				'href' => $this->url->link ( 'extension/extension', 'user_token=' . $this->session->data ['user_token'] . '&type=module', true ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'extension/total/cardgatefee', 'user_token=' . $this->session->data ['user_token'], true ),
				'separator' => ' :: ' 
		);
		
		$data ['action'] = $this->url->link ( 'extension/total/cardgatefee', 'user_token=' . $this->session->data ['user_token'], true );
		
		$data ['cancel'] = $this->url->link ( 'marketplace/extension', 'user_token=' . $this->session->data ['user_token'] . '&type=total', true );
		
		for($i = 1; $i <= 12; $i ++) {
			if (isset ( $this->request->post ['total_cardgatefee_name' . $i] )) {
				$data ['method_data'] [$i] ['total_cardgatefee_name'] = $this->request->post ['total_cardgatefee_name' . $i];
			} else {
				$data ['method_data'] [$i] ['total_cardgatefee_name'] = $this->config->get ( 'total_cardgatefee_name' . $i );
			}
			
			if (isset ( $this->request->post ['total_cardgatefee_cost_percentage' . $i] )) {
			    $data ['method_data'] [$i] ['total_cardgatefee_cost_percentage'] = $this->request->post ['total_cardgatefee_cost_percentage' . $i];
			} else {
			    $data ['method_data'] [$i] ['total_cardgatefee_cost_percentage'] = $this->config->get ( 'total_cardgatefee_cost_percentage' . $i );
			}
			
			if (isset ( $this->request->post ['total_cardgatefee_cost' . $i] )) {
				$data ['method_data'] [$i] ['total_cardgatefee_cost'] = $this->request->post ['total_cardgatefee_cost' . $i];
			} else {
				$data ['method_data'] [$i] ['total_cardgatefee_cost'] = $this->config->get ( 'total_cardgatefee_cost' . $i );
			}
			
			if (isset ( $this->request->post ['total_cardgatefee_free' . $i] )) {
				$data ['method_data'] [$i] ['total_cardgatefee_free'] = $this->request->post ['total_cardgatefee_free' . $i];
			} else {
				$data ['method_data'] [$i] ['total_cardgatefee_free'] = $this->config->get ( 'total_cardgatefee_free' . $i );
			}
			
			if (isset ( $this->request->post ['total_cardgatefee_tax_class_id' . $i] )) {
				$data ['method_data'] [$i] ['total_cardgatefee_tax_class_id'] = $this->request->post ['total_cardgatefee_tax_class_id' . $i];
			} else {
				$data ['method_data'] [$i] ['total_cardgatefee_tax_class_id'] = $this->config->get ( 'total_cardgatefee_tax_class_id' . $i );
			}
			
			if (isset ( $this->request->post ['total_cardgatefee_geo_zone_id' . $i] )) {
				$data ['method_data'] [$i] ['total_cardgatefee_geo_zone_id'] = $this->request->post ['total_cardgatefee_geo_zone_id' . $i];
			} else {
				$data ['method_data'] [$i] ['total_cardgatefee_geo_zone_id'] = $this->config->get ( 'total_cardgatefee_geo_zone_id' . $i );
			}
			
			if (isset ( $this->request->post ['total_cardgatefee_status' . $i] )) {
				$data ['method_data'] [$i] ['total_cardgatefee_status'] = $this->request->post ['total_cardgatefee_status' . $i];
			} else {
				$data ['method_data'] [$i] ['total_cardgatefee_status'] = $this->config->get ( 'total_cardgatefee_status' . $i );
			}
			
			if (isset ( $this->request->post ['total_cardgatefee_shipping' . $i] )) {
				$data ['method_data'] [$i] ['total_cardgatefee_shipping'] = $this->request->post ['total_cardgatefee_shipping' . $i];
			} else {
				$data ['method_data'] [$i] ['total_cardgatefee_shipping'] = $this->config->get ( 'total_cardgatefee_shipping' . $i );
			}
			
			if (isset ( $this->request->post ['total_cardgatefee_payment' . $i] )) {
				$data ['method_data'] [$i] ['total_cardgatefee_payment'] = $this->request->post ['total_cardgatefee_payment' . $i];
			} else {
				$data ['method_data'] [$i] ['total_cardgatefee_payment'] = $this->config->get ( 'total_cardgatefee_payment' . $i );
			}
			
			if (isset ( $this->request->post ['total_cardgatefee_total' . $i] )) {
				$data ['method_data'] [$i] ['total_cardgatefee_total'] = $this->request->post ['total_cardgatefee_total' . $i];
			} else {
				$data ['method_data'] [$i] ['total_cardgatefee_total'] = $this->config->get ( 'total_cardgatefee_total' . $i );
			}
			
			if (isset ( $this->request->post ['total_cardgatefee_total_max' . $i] )) {
				$data ['method_data'] [$i] ['total_cardgatefee_total_max'] = $this->request->post ['total_cardgatefee_total_max' . $i];
			} else {
				$data ['method_data'] [$i] ['total_cardgatefee_total_max'] = $this->config->get ( 'total_cardgatefee_total_max' . $i );
			}
			
			if (isset ( $this->request->post ['total_cardgatefee_sort_order' . $i] )) {
				$data ['method_data'] [$i] ['total_cardgatefee_sort_order'] = $this->request->post ['total_cardgatefee_sort_order' . $i];
			} else {
				$data ['method_data'] [$i] ['total_cardgatefee_sort_order'] = $this->config->get ( 'total_cardgatefee_sort_order' . $i );
			}
		}
		
		if (isset ( $this->request->post ['total_cardgatefee_status'] )) {
			$data ['total_cardgatefee_status'] = $this->request->post ['total_cardgatefee_status'];
		} else {
			$data ['total_cardgatefee_status'] = $this->config->get ( 'total_cardgatefee_status' );
		}
		if (isset ( $this->request->post ['total_cardgatefee_sort_order'] )) {
			$data ['total_cardgatefee_sort_order'] = $this->request->post ['total_cardgatefee_sort_order'];
		} else {
			$data ['total_cardgatefee_sort_order'] = $this->config->get ( 'total_cardgatefee_sort_order' );
		}
		
		$this->load->model ( 'localisation/tax_class' );
		
		$data ['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses ();
		
		$this->load->model ( 'localisation/geo_zone' );
		
		$data ['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones ();
		
		$shipping_mods = array ();
		$xshipping_installed = false;
		$result = $this->db->query ( "select * from " . DB_PREFIX . "extension where type='shipping'" );
		if ($result->rows) {
			$i = 0;
			foreach ( $result->rows as $row ) {
				$i ++;
				$shipping_mods [$i] ['code'] = $row ['code'];
				$shipping_mods [$i] ['name'] = $this->getModuleName ( $row ['code'], $row ['type'] );
				if ($row ['code'] == 'xshippingpro')
					$xshipping_installed = true;
			}
		}
		
		$data ['shipping_mods'] = $shipping_mods;
		
		$xshippingpro_methods = array ();
		/* For X-Shipping Pro */
		if ($xshipping_installed) {
			$language_id = $this->config->get ( 'config_language_id' );
			$xshippingpro = $this->config->get ( 'xshippingpro' );
			if ($xshippingpro) {
				$xshippingpro = unserialize ( base64_decode ( $xshippingpro ) );
			}
			
			if (! isset ( $xshippingpro ['name'] ))
				$xshippingpro ['name'] = array ();
			if (! is_array ( $xshippingpro ['name'] ))
				$xshippingpro ['name'] = array ();
			
			$i = 0;
			foreach ( $xshippingpro ['name'] as $no_of_tab => $names ) {
				$i ++;
				if (isset ( $names [$language_id] ) && $names [$language_id]) {
					$xshippingpro_methods [$i] ['code'] = 'xshippingpro' . '.xshippingpro' . $no_of_tab;
					$xshippingpro_methods [$i] ['name'] = $names [$language_id];
				}
			}
		}
		/* End of X-shipping Pro */
		$data ['xshippingpro_methods'] = $xshippingpro_methods;
		
		$payment_mods = array ();
		$cardgate_installed = false;
		$result = $this->db->query ( "select * from " . DB_PREFIX . "extension where type='payment' && code LIKE 'cardgate%' && code <>'cardgate'" );
		if ($result->rows) {
			$i = 0;
			foreach ( $result->rows as $row ) {
				$i++;
				$payment_mods[$i]['code'] = $row['code'];
				$payment_mods[$i]['name'] = $this->getModuleName ( $row ['code'], $row ['type'] );
				
				if ($row ['code'] == 'cardgate')
					$cardgate_installed = true;
			}
		}
		
		$data ['payment_mods'] = $payment_mods;
		
		$cardgatepayment_methods = array ();
		/* For CardGate Payment */
		if ($cardgate_installed) {
			$language_id = $this->config->get ( 'config_language_id' );
			$cardgatepayment = $this->config->get ( 'cardgatepayment' );
			if ($cardgatepayment) {
				$cardgatepayment = unserialize ( base64_decode ( $cardgatepayment ) );
			}
			
			if (! isset ( $cardgatepayment ['name'] ))
				$cardgatepayment ['name'] = array ();
			if (! is_array ( $cardgatepayment ['name'] ))
				$cardgatepayment ['name'] = array ();
			
			foreach ( $cardgatepayment ['name'] as $no_of_tab => $names ) {
				
				if (isset ( $names [$language_id] ) && $names [$language_id]) {
					$code = 'cardgatepayment' . '.cardgatepayment' . $no_of_tab;
					$cardgatepayment_methods [$code] = $names [$language_id];
				}
			}
		}
		
		$data ['cardgatepayments'] = $cardgatepayment_methods;

		/* End of CardGate Payment */
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'extension/total/cardgatefee', $data ) );
	}
	protected function validate() {
		if (! $this->user->hasPermission ( 'modify', 'extension/total/cardgatefee' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		if (! $this->error) {
			return true;
		} else {
			return false;
		}
	}
	function getModuleName($code, $type) {
		if (! $code)
			return '';
		
		$this->language->load ( 'extension/' . $type . '/' . $code );
		return $this->language->get ( 'heading_title' );
	}
}
?>