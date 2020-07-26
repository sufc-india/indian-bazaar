<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGatePayPal extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgatepaypal');
	}

	public function validate() {
		return $this->_validate('cardgatepaypal');
	}
}
?>