<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateSofortBanking extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgatesofortbanking');
	}

	public function validate() {
		return $this->_validate('cardgatesofortbanking');
	}
}
?>