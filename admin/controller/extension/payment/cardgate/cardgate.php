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
 * @copyright   Copyright (c) 2014 CardGatePlus B.V. (http://www.cardgateplus.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
class ControllerExtensionPaymentCardGatePlusGeneric extends Controller {

    public $error = array();

    public function _index( $payment ) {

        //update version also in catalog/controller/payment/cardgate/cardgate.php
        $version = '3.0.17';
         
        $this->load->language( 'extension/payment/' . $payment );
        $this->document->setTitle( $this->language->get( 'heading_title' ) );
        $this->load->model( 'setting/setting' );
        $this->error = array();

        $site_url = HTTPS_CATALOG;

        if ( ($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate()) ) {
            $this->cache->set('cardgateissuerrefresh', 0);
            $this->request->post['payment_cardgate_use_logo'] = (isset($this->request->post['payment_cardgate_use_logo']) ? 1 : 0);
            $this->request->post['payment_cardgate_use_title'] = (isset($this->request->post['payment_cardgate_use_title']) ? 1 : 0);
            $this->model_setting_setting->editSetting( 'payment_'.$payment, $this->request->post );
            $this->session->data['success'] = $this->language->get( 'text_success' );
            $this->response->redirect( $this->url->link( 'marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=payment', true) );
        }
       
        $data['heading_title'] = $this->language->get( 'heading_title' );
        $data['text_general'] = $this->language->get( 'text_general' );
        $data['text_order_status'] = $this->language->get( 'text_order_status' );
        $data['text_info'] = $this->language->get( 'text_info' );
        $data['text_enabled'] = $this->language->get( 'text_enabled' );
        $data['text_disabled'] = $this->language->get( 'text_disabled' );
        $data['text_all_zones'] = $this->language->get( 'text_all_zones' );
        $data['text_test_mode'] = $this->language->get( 'text_test_mode' );
        $data['text_live_mode'] = $this->language->get( 'text_live_mode' );
        $data['text_language_dutch'] = $this->language->get( 'text_language_dutch' );
        $data['text_language_english'] = $this->language->get( 'text_language_english' );
        $data['text_language_german'] = $this->language->get( 'text_language_german' );
        $data['text_language_french'] = $this->language->get( 'text_language_french' );
        $data['text_language_spanish'] = $this->language->get( 'text_language_spanish' );
        $data['text_language_greek'] = $this->language->get( 'text_language_greek' );
        $data['text_language_croatian'] = $this->language->get( 'text_language_croatian' );
        $data['text_language_italian'] = $this->language->get( 'text_language_italian' );
        $data['text_language_czech'] = $this->language->get( 'text_language_czech' );
        $data['text_language_russian'] = $this->language->get( 'text_language_russian' );
        $data['text_language_swedish'] = $this->language->get( 'text_language_swedish' );
        $data['text_set_order_status'] = $this->language->get( 'text_set_order_status' );
        $data['text_cardgate_version'] = $version;
        $data['text_author'] = $this->language->get( 'text_author' );
        $data['text_test_mode_help'] = $this->language->get( 'text_test_mode_help' );
        $data['text_site_id'] = $this->language->get( 'text_site_id' );
        $data['text_hash_key'] = $this->language->get( 'text_hash_key' );
        $data['text_merchant_id'] = $this->language->get( 'text_merchant_id' );
        $data['text_api_key'] = $this->language->get( 'text_api_key' );
        $data['text_gateway_language'] = $this->language->get( 'text_gateway_language' );
        $data['text_order_description'] = $this->language->get( 'text_order_description' );
        $data['text_use_logo'] = $this->language->get( 'text_use_logo' );
        $data['text_use_title'] = $this->language->get( 'text_use_title' );
        $data['text_custom_payment_method_text'] = $this->language->get('text_custom_payment_method_text');
        $data['text_total'] = $this->language->get( 'text_total' );
        $data['text_site_url'] = $site_url . 'index.php?route=extension/payment/cardgategeneric/control';
        $data['text_control_url'] = $this->language->get( 'text_control_url' );
        $data['text_plugin_version'] = $this->language->get( 'text_plugin_version' );
        $data['text_author'] = $this->language->get( 'text_author' );
        $data['text_note_control_url'] = $this->language->get( 'text_note_control_url' );
       
        $data['entry_test_mode'] = $this->language->get( 'entry_test_mode' );
        $data['entry_site_id'] = $this->language->get( 'entry_site_id' );
        $data['entry_hash_key'] = $this->language->get( 'entry_hash_key' );
        $data['entry_merchant_id'] = $this->language->get( 'entry_merchant_id' );
        $data['entry_api_key'] = $this->language->get( 'entry_api_key' );
        
        $data['entry_gateway_language'] = $this->language->get( 'entry_gateway_language' );
        
        $data['entry_custom_payment_method_text'] = $this->language->get('entry_custom_payment_method_text');
        $data['entry_total'] = $this->language->get( 'entry_total' );
        $data['entry_geo_zone'] = $this->language->get( 'entry_geo_zone' );
        $data['entry_order_description'] = $this->language->get( 'entry_order_description' );
        $data['entry_use_logo'] = $this->language->get( 'entry_use_logo' );
        $data['entry_use_title'] = $this->language->get( 'entry_use_title' );
        $data['entry_payment_initialized_status'] = $this->language->get( 'entry_payment_initialized_status' );
        $data['entry_payment_complete_status'] = $this->language->get( 'entry_payment_complete_status' );
        $data['entry_payment_failed_status'] = $this->language->get( 'entry_payment_failed_status' );
        $data['entry_payment_fraud_status'] = $this->language->get( 'entry_payment_fraud_status' );
        $data['entry_plugin_status'] = $this->language->get( 'entry_plugin_status' );
        $data['entry_sort_order'] = $this->language->get( 'entry_sort_order' );
        $data['entry_author'] = $this->language->get( 'entry_author' );
        $data['entry_plugin_version'] = $version;

        $data['button_save'] = $this->language->get( 'button_save' );
        $data['button_cancel'] = $this->language->get( 'button_cancel' );
        $data['tab_general'] = $this->language->get( 'tab_general' );

        if ( isset( $this->error['warning'] ) ) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if ( isset( $this->error['site_id'] ) ) {
            $data['error_site_id'] = $this->error['site_id'];
        } else {
            $data['error_site_id'] = '';
        }
        
        if ( isset( $this->error['merchant_id'] ) ) {
            $data['error_merchant_id'] = $this->error['merchant_id'];
        } else {
            $data['error_merchant_id'] = '';
        }
        
        if ( isset( $this->error['api_key'] ) ) {
            $data['error_api_key'] = $this->error['api_key'];
        } else {
            $data['error_api_key'] = '';
        }

        if ( isset( $this->error['payment_method'] ) ) {
            $data['error_payment_method'] = $this->error['payment_method'];
        } else {
            $data['error_payment_method'] = '';
        }
        
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'href' => $this->url->link( 'common/home', 'user_token=' . $this->session->data['user_token'], true ),
            'text' => $this->language->get( 'text_home' ),
            'separator' => false
        );
        $data['breadcrumbs'][] = array(
            'href' => $this->url->link( 'extension/payment', 'user_token=' . $this->session->data['user_token'], true ),
            'text' => $this->language->get( 'text_payment' ),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'href' => $this->url->link( 'payment/' . $payment, 'user_token=' . $this->session->data['user_token'], true ),
            'text' => $this->language->get( 'heading_title' ),
            'separator' => ' :: '
        );
        $data['action'] = $this->url->link( 'extension/payment/' . $payment, 'user_token=' . $this->session->data['user_token'], true );
        $data['cancel'] = $this->url->link( 'marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true );
       

        if ( isset( $this->request->post['payment_'.$payment . '_site_id'] ) ) {
            $data['payment_'.$payment . '_site_id'] = $this->request->post['payment_'.$payment . '_site_id'];
        } else {
            $data['payment_'.$payment . '_site_id'] = $this->config->get( 'payment_'.$payment . '_site_id' );
        }
        
        if ( isset( $this->request->post['payment_'.$payment . '_hash_key'] ) ) {
            $data['payment_'.$payment . '_hash_key'] = $this->request->post['payment_'.$payment . '_hash_key'];
        } else {
            $data['payment_'.$payment . '_hash_key'] = $this->config->get( 'payment_'.$payment . '_hash_key' );
        }
        
        if ( isset( $this->request->post['payment_'.$payment . '_merchant_id'] ) ) {
            $data['payment_'.$payment . '_merchant_id'] = $this->request->post['payment_'.$payment . '_merchant_id'];
        } else {
            $data['payment_'.$payment . '_merchant_id'] = $this->config->get( 'payment_'.$payment . '_merchant_id' );
        }
        
        if ( isset( $this->request->post['payment_'.$payment . '_api_key'] ) ) {
            $data['payment_'.$payment . '_api_key'] = $this->request->post['payment_'.$payment . '_api_key'];
        } else {
            $data['payment_'.$payment . '_api_key'] = $this->config->get( 'payment_'.$payment . '_api_key' );
        }

        if ( isset( $this->request->post['payment_'.$payment . '_test_mode'] ) ) {
            $data['payment_'.$payment . '_test_mode'] = $this->request->post['payment_'.$payment . '_test_mode'];
        } else {
            $data['payment_'.$payment . '_test_mode'] = $this->config->get( 'payment_'.$payment . '_test_mode' );
        }

        if ( isset( $this->request->post['payment_'.$payment . '_order_description'] ) ) {
            $data['payment_'.$payment . '_order_description'] = $this->request->post['payment_'.$payment . '_order_description'];
        } else {
            $data['payment_'.$payment . '_order_description'] = $this->config->get( 'payment_'.$payment . '_order_description' );
        }
        
        if ( isset( $this->request->post['payment_'.$payment . '_use_logo'] ) ) {
            $data['payment_'.$payment . '_use_logo'] = $this->request->post['payment_'.$payment . '_use_logo'];
        } else {
            $data['payment_'.$payment . '_use_logo'] = $this->config->get( 'payment_'.$payment . '_use_logo' );
        }
        
        if ( isset( $this->request->post['payment_'.$payment . '_use_title'] ) ) {
            $data['payment_'.$payment . '_use_title'] = $this->request->post['payment_'.$payment . '_use_title'];
        } else {
            $data['payment_'.$payment . '_use_title'] = $this->config->get( 'payment_'.$payment . '_use_title' );
        }

        if ( isset( $this->request->post['payment_'.$payment . '_payment_initialized_status'] ) ) {
            $data['payment_'.$payment . '_payment_initialized_status'] = $this->request->post['payment_'.$payment . '_payment_initialized_status'];
        } else {
            $data['payment_'.$payment . '_payment_initialized_status'] = $this->config->get( 'payment_'.$payment . '_payment_initialized_status' );
        }

        if ( isset( $this->request->post['payment_'.$payment . '_payment_complete_status'] ) ) {
            $data['payment_'.$payment . '_payment_complete_status'] = $this->request->post['payment_'.$payment . '_payment_complete_status'];
        } else {
            $data['payment_'.$payment . '_payment_complete_status'] = $this->config->get( 'payment_'.$payment . '_payment_complete_status' );
        }

        if ( isset( $this->request->post['payment_'.$payment . '_payment_failed_status'] ) ) {
            $data['payment_'.$payment . '_payment_failed_status'] = $this->request->post['payment_'.$payment . '_payment_failed_status'];
        } else {
            $data['payment_'.$payment . '_payment_failed_status'] = $this->config->get( 'payment_'.$payment . '_payment_failed_status' );
        }

        if ( isset( $this->request->post['payment_'.$payment . '_payment_fraud_status'] ) ) {
            $data['payment_'.$payment . '_payment_fraud_status'] = $this->request->post['payment_'.$payment . '_payment_fraud_status'];
        } else {
            $data['payment_'.$payment . '_payment_fraud_status'] = $this->config->get( 'payment_'.$payment . '_payment_fraud_status' );
        }

        $this->load->model( 'localisation/order_status' );
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if ( isset( $this->request->post['payment_'.$payment . '_custom_payment_method_text'] ) ) {
            $data['payment_'.$payment . '_total'] = $this->request->post['payment_'.$payment . '_custom_payment_method_text'];
        } else {
            $data['payment_'.$payment . '_custom_payment_method_text'] = $this->config->get( 'payment_'.$payment . '_custom_payment_method_text' );
        }
        
        if ( isset( $this->request->post['payment_'.$payment . '_total'] ) ) {
            $data['payment_'.$payment . '_total'] = $this->request->post['payment_'.$payment . '_total'];
        } else {
            $data['payment_'.$payment . '_total'] = $this->config->get( 'payment_'.$payment . '_total' );
        }

        if ( isset( $this->request->post['payment_'.$payment . '_geo_zone_id'] ) ) {
            $data['payment_'.$payment . '_geo_zone_id'] = $this->request->post['payment_'.$payment . '_geo_zone_id'];
        } else {
            $data['payment_'.$payment . '_geo_zone_id'] = $this->config->get( 'payment_'.$payment . '_geo_zone_id' );
        }

        $this->load->model( 'localisation/geo_zone' );
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if ( isset( $this->request->post['payment_'.$payment . '_status'] ) ) {
            $data['payment_'.$payment . '_status'] = $this->request->post['payment_'.$payment . '_status'];
        } else {
            $data['payment_'.$payment . '_status'] = $this->config->get( 'payment_'.$payment . '_status' );
        }

        if ( isset( $this->request->post['payment_'.$payment . '_sort_order'] ) ) {
            $data['payment_'.$payment . '_sort_order'] = $this->request->post['payment_'.$payment . '_sort_order'];
        } else {
            $data['payment_'.$payment . '_sort_order'] = $this->config->get( 'payment_'.$payment . '_sort_order' );
        }
        

        $data['header'] = $this->load->controller( 'common/header' );
        $data['column_left'] = $this->load->controller( 'common/column_left' );
        $data['footer'] = $this->load->controller( 'common/footer' );
        $this->response->setOutput( $this->load->view( 'extension/payment/' . $payment, $data ) );
    }

    public function _validate( $payment ) {
        
        if ( !$this->user->hasPermission( 'modify', 'extension/payment/' . $payment ) ) {
            $this->error['warning'] = $this->language->get( 'error_permission' );
        }

        if ($payment == 'cardgate' && !$this->request->post['payment_'.$payment . '_site_id'] ) {
            $this->error['site_id'] = $this->language->get( 'error_site_id' );
        }
        
        if ( $payment == 'cardgate' && !$this->request->post['payment_'.$payment . '_merchant_id'] ) {
            $this->error['merchant_id'] = $this->language->get( 'error_merchant_id' );
        }
        
        if ( $payment == 'cardgate' && !$this->request->post['payment_'.$payment . '_api_key'] ) {
            $this->error['api_key'] = $this->language->get( 'error_api_key' );
        }
        
        if ( !$this->error ) {
            return true;
        } else {
            return false;
        }
    }

}
