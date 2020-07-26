<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateKlarna extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgateklarna');
	}

	public function validate() {
		return $this->_validate('cardgateklarna');
	}
}
?>