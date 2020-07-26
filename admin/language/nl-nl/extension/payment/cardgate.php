<?php
/**
 * Opencart CardGatePlus payment extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    Payment
 * @package     Payment_CardGatePlus
 * @author      Richard Schoots, <info@cardgate.com>
 * @copyright   Copyright (c) 2014 CardGatePlus B.V. (http://www.cardgateplus.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

// Heading
$_['heading_title']          = 'Cardgate Algemeen';

// Text 
$_['text_general']           = 'Algemeen';
$_['text_order_status']      = 'Order Status';
$_['text_info']              = 'Info';
$_['text_payment']           = 'Betaling';
$_['text_success']           = 'Gelukt: U heeft uw Cardgate Algemeen instellingen gewijzigd!';
$_['text_cardgateplus']      = '<a onclick="window.open(\'http://www.cardgate.com/\');"><img src="view/image/payment/cardgateplus.png" alt="Cardate Algemeen" title="Cardgate Algemeen" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_cardgate']          = $_['text_cardgateplus'];
$_['text_test_mode']         = 'Test mode';
$_['text_live_mode']         = 'Live mode';
$_['text_language_dutch']    = 'Nederlands';
$_['text_language_english']  = 'Engels';
$_['text_language_german']   = 'Duits';
$_['text_language_french']   = 'Frans';
$_['text_language_spanish']  = 'Spaans';
$_['text_language_greek']    = 'Grieks';
$_['text_language_croatian'] = 'Kroatisch';
$_['text_language_italian']  = 'Italiaans';
$_['text_language_czech']    = 'Tsjechisch';
$_['text_language_russian']  = 'Russisch';
$_['text_language_swedish']  = 'Zweeds';
$_['text_set_order_status']  = 'Stel Order Status in';
$_['text_author']            = '<a href="http://www.cardgate.com/" target="_blank">www.cardgate.com</a>';
$_['text_test_mode_help']    = 'U gaat nu van Test naar Live modus. Indien u geen account heeft, schrijf u dan in bij http://www.cardgate.com/ .';
$_['text_site_id']           = 'Vul hier uw site ID in. Deze kunt u vinden in de Cardgate merchant backoffice.';
$_['text_hash_key']          = 'Vul hier uw hash key in. Indien u de hash key gebruikt, zorg er dan voor dat deze hetzelfde is als in uw Cardgate merchant backoffice.';

$_['text_merchant_id']       = 'Vul hier uw merchant ID in. Deze kunt u vinden in de Cardgate merchant backoffice.';
$_['text_api_key']           = 'Vul hier uw API key in . Die heeft u gekregen van uw account manager';
$_['text_gateway_language']  = 'Stel een standaard taal in voor de gateway.';
$_['text_order_description'] = 'Omschrijving voor de betaling welke getoond zal worden in het betaalscherm. Variabelen: <b>%id%</b> = Order ID';
$_['text_use_logo']          = 'Toon het logo van de betaalmehtode in de checkout';
$_['text_use_title']         = 'Toon de betaalmethode naam in de checkout';
$_['text_total']             = 'Het order totaal moet boven dit bedrag zijn om de betaal methode te tonen.';
$_['text_control_url']       = 'Control Url:';
$_['text_plugin_version']    = 'Plugin versie';
$_['text_author']            = 'Auteur:';

// Entry
$_['entry_test_mode']         = 'Test/Live Modus:';
$_['entry_site_id']           = 'Site ID:';
$_['entry_hash_key']          = 'Hash key:';
$_['entry_merchant_id']       = 'Merchant ID:';
$_['entry_api_key']           = 'API key:';
$_['entry_payment_title']     = 'Titel:';
$_['entry_gateway_language']  = 'Gateway Taal:';
$_['entry_order_description'] = 'Omschrijving Bestelling:';
$_['entry_use_logo']          = 'Gebruik Logo';
$_['entry_use_title']         = 'Gebruik betaalmethode naam';
$_['entry_total']             = 'Totaal:';
$_['entry_geo_zone']          = 'Geo Zone:';

$_['entry_payment_initialized_status'] = 'Betaling Geinitieerd status:';
$_['entry_payment_complete_status']    = 'Betaling Succesvol status:';
$_['entry_payment_failed_status']      = 'Betaling Mislukt status:';
$_['entry_payment_fraud_status']       = 'Betaling Mogelijke Fraude status:';

$_['entry_plugin_status']    = 'Plugin Status:';
$_['entry_sort_order']       = 'Sorteer Volgorde:';
$_['entry_version_status']   = 'Plugin Versie:';
$_['entry_author']           = '<a href="http_//www.cardgate.com">cardgate</a>';

// Error
$_['error_permission']       = 'Waarschuwing: U heeft geen toestemming om de betaal methode Card Gate Plus te wijzigen!';
$_['error_site_id']          = 'Site ID vereist!';
$_['error_merchant_id']      = 'Merchant ID vereist!';
$_['error_api_key']          = 'API key vereist!';
$_['error_payment_method']   = 'Selecteer minimaal een betaal methode.';