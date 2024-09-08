<?php
error_reporting(E_ERROR);
session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/secret.inc';
require __DIR__ . '/Timeslots.php';
require_once __DIR__ . '/BARKNotifier.php';

use Twilio\Rest\Client;

//Check if we are actually in the lab hours timeslot.json
//If user is not logged in, check all the conditions
if(empty($_SESSION["user"]))
{
    $timeSlotsObj = new Timeslots();
    $slotDetails = $timeSlotsObj->checkCurrentMeeting();
    if (!$slotDetails) {
        echo "It's not open lab hours right now";
        return;
    }

//If the meeting has started, don't make the call. read from meeting_status.txt
    if (file_exists('meeting_status.txt') && file_get_contents('meeting_status.txt') == 'started') {
        echo "Meeting has already started";
        return;
    }

//Check if the last call was made less than 60 seconds ago
    if (file_exists('config/last_call.txt') && time() - file_get_contents('config/last_call.txt') < 60) {
        echo "TOOSOON";
        return;
    }
}

BARKNotifier::notifyTimeSensitive("ITP 303 lab hours NOW. someone joined!");


//From Twilio Docs: https://www.twilio.com/docs/voice/quickstart/php#make-an-outgoing-phone-call-with-php
// Your Account SID and Auth Token from twilio.com/console
// To set up environmental variables, see http://twil.io/secure
$account_sid = TWILIO_ACCOUNT_SID;
$auth_token = TWILIO_AUTH_TOKEN;
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

// A Twilio number you own with Voice capabilities
$twilio_number = TWILIO_PHONE_NUMBER;

// Where to make a voice call (your cell phone?)
$to_number = MY_PHONE_NUMBER;

$client = new Client($account_sid, $auth_token);
$client->account->calls->create(
    $to_number,
    $twilio_number,
    array(
        "url" => "http://demo.twilio.com/docs/voice.xml"
    )
);

//Store the current timestamp in a file
file_put_contents('config/last_call.txt', time());


echo "OK";
