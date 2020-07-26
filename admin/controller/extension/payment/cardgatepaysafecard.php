<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGatePaysafecard extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgatepaysafecard');
	}

	public function validate() {
		return $this->_validate('cardgatepaysafecard');
	}
}
?>