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
 * @author      Paul Saparov, <pavel@cardgate.com>
 * @copyright   Copyright (c) 2012 CardGatePlus B.V. (http://www.cardgateideal.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
include 'cardgate.php';

class ControllerExtensionPaymentCardGateIdeal extends ControllerExtensionPaymentCardGate {

     public function index() {
         return $this->_index('cardgateideal');
     }
     
     public function confirm() {
         $this->_confirm('cardgateideal');
     }
}
