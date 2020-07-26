<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGatePaysafecash extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgatepaysafecash');
	}

	public function validate() {
		return $this->_validate('cardgatepaysafecash');
	}
}
?>