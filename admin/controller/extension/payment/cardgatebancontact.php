<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateBancontact extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgatebancontact');
	}

	public function validate() {
		return $this->_validate('cardgatebancontact');
	}
}
?>