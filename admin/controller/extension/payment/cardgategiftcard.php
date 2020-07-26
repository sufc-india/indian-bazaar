<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateGiftcard extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgategiftcard');
	}

	public function validate() {
		return $this->_validate('cardgategiftcard');
	}
}
?>