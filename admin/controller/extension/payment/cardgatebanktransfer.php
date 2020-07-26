<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateBanktransfer extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgatebanktransfer');
	}

	public function validate() {
		return $this->_validate('cardgatebanktransfer');
	}
}
?>