<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGatePrzelewy24 extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgateprzelewy24');
	}

	public function validate() {
		return $this->_validate('cardgateprzelewy24');
	}
}
?>