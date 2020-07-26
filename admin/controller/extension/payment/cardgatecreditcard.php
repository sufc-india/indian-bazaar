<?php 

include 'cardgate/cardgate.php';

class ControllerExtensionPaymentCardGateCreditcard extends ControllerExtensionPaymentCardGatePlusGeneric {
	public function index() {
		$this->_index('cardgatecreditcard');
	}

	public function validate() {
		return $this->_validate('cardgatecreditcard');
	}
}
?>