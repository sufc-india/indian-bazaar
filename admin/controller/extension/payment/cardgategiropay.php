<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateGiropay extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgategiropay');
	}

	public function validate() {
		return $this->_validate('cardgategiropay');
	}
}
?>