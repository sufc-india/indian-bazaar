<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateBillink extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgatebillink');
	}

	public function validate() {
		return $this->_validate('cardgatebillink');
	}
}
?>