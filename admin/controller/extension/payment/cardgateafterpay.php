<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateAfterpay extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgateafterpay');
	}

	public function validate() {
		return $this->_validate('cardgateafterpay');
	}
}
?>