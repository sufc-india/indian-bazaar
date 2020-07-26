<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGate extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgate');
	}

	public function validate() {
		return $this->_validate('cardgate');
	}
}
?>