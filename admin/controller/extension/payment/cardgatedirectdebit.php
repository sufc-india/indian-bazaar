<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateDirectDebit extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgatedirectdebit');
	}

	public function validate() {
		return $this->_validate('cardgatedirectdebit');
	}
}
?>