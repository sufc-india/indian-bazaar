<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateOnlineueberweisen extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgateonlineueberweisen');
	}

	public function validate() {
		return $this->_validate('cardgateonlineueberweisen');
	}
}
?>