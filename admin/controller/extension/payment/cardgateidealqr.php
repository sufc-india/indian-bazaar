<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateIdealqr extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgateidealqr');
	}

	public function validate() {
		return $this->_validate('cardgateidealqr');
	}
}
?>