<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateIdeal extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgateideal');
	}

	public function validate() {
		return $this->_validate('cardgateideal');
	}
}
?>