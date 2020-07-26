<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateBitcoin extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgatebitcoin');
	}

	public function validate() {
		return $this->_validate('cardgatebitcoin');
	}
}
?>