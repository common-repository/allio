<?php
/**
 * AJAX Functions
 *
 * Process the front-end AJAX actions.
 *
 * @package     ALLIOC
 * @subpackage  Functions/ALLIOC
 * @copyright   Copyright (c) 2017, Daniel Powney
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if (! defined ( 'ABSPATH' ))
	exit ();

add_action( 'wp_ajax_query_text', 'allioc_query_text' );
add_action( 'wp_ajax_nopriv_query_text', 'allioc_query_text');


include_once dirname( __FILE__ ) . '/../dialogflow/vendor/autoload.php';
require_once dirname( __FILE__ ) . '/../dialogflow/lib.php';
require_once ALLIOC_PLUGIN_DIR . '/vendor/autoload.php'; 

use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\EventInput;

function allioc_get_query_result($sessionId, $message, $event, $languageCode, $token = '', $charge = '', $sk = '') {
    $general_settings = (array) get_option( 'allioc_general_settings' );

    $arr = json_decode($general_settings['allioc_access_token'], true);
    $projectId = "";
    if (!empty($arr)) {
        $projectId = $arr["project_id"];
    }

    $upload_dir = wp_upload_dir();
    $channel = array('credentials' => $upload_dir['basedir'] . "/dialogflow_settings.json");
    $sessionsClient = new SessionsClient($channel);
    $session = $sessionsClient->sessionName($projectId, $sessionId ?: uniqid());

        // Create query input
    $queryInput = new QueryInput();
    if ($message) {
        // Create text input
        $textInput = new TextInput();
        $textInput->setText($message);
        $textInput->setLanguageCode($languageCode);
        $queryInput->setText($textInput);
    }

    if ($event) {
        $eventInput = new EventInput();
        $eventInput->setName($event);
        $eventInput->setLanguageCode($languageCode);
        $queryInput->setEvent($eventInput);
    }

    if ($charge == 0) {
        $charge = 20;
    }

    if ($token && $charge && $sk) {

        \Stripe\Stripe::setApiKey($sk);
        $charge = \Stripe\Charge::create(['amount' => $charge * 100, 'currency' => 'usd', 'source' => $token, 'description' => 'charge']);

        if ($charge->status != "succeeded") {
            $textInput = new TextInput();
            $textInput->setText("PaymentCompleted");
            $textInput->setLanguageCode($languageCode);
            $queryInput->setText($textInput);
        }
    }
    // Get response and close connection
    $detectedIntentResponse = $sessionsClient->detectIntent($session, $queryInput);
    $sessionsClient->close();
    // Serialize Query Result to JSON
    $responseBody = $detectedIntentResponse->getQueryResult()->serializeToJsonString();

    // Normalize the JSON string by adding null values
    $responseBody = allioc_normalizeJSON($responseBody);
    
    return $responseBody;
}

function allioc_query_text() {
	check_ajax_referer('allioc-!@#$%^', 'security');

    $sessionId = isset($_POST['session']) ? sanitize_text_field($_POST['session']) : 'stargroup';

    $message = isset($_POST['message']) ? sanitize_text_field($_POST['message']) : 'hi';

    $event =  isset($_POST['event']) ? sanitize_text_field($_POST['event']) : '';

    $languageCode =isset($_POST['language']) ? sanitize_text_field($_POST['language']) : 'en';

    $token =  isset($_POST['token']) ? sanitize_text_field($_POST['token']) : '';

    $charge =isset($_POST['charge']) ? floatval(sanitize_text_field($_POST['charge'])) : 0;

    $sk =isset($_POST['sk']) ? floatval(sanitize_text_field($_POST['sk'])) : '';

    
    echo allioc_get_query_result($sessionId, $message, $event, $languageCode, $token, $charge, $sk);
    die();
}