<?php

class TwilioController extends Controller {

    public function index() {
        return View::make('twilio');
    }

    public function send() {

        // Get form inputs
        $to = Input::get('phoneNumber');
        $from = Config::get('twilio.TWILIO_NUMBER');
        $message = Input::get('message');

        // Create an authenticated client for the Twilio API
        $client = new Services_Twilio(Config::get('twilio.TWILIO_ACCOUNT_SID'), Config::get('twilio.TWILIO_AUTH_TOKEN'), "2014-12-08");

        try {
            // Use the Twilio REST API client to send a text message
            $m = $client->account->messages->sendMessage(
                    $from, // the text will be sent from your Twilio number
                    $to, // the phone number the text will be sent to
                    $message // the body of the text message
            );

            /* $m = $client->account->messages->create(array(
              'To' => $to,
              'From' => $from,
              'Body' => $message,
              )); */
        } catch (Services_Twilio_RestException $e) {
            // Return and render the exception object, or handle the error as needed
            return $e;
        };

        // Return the message object to the browser as JSON
        return $m;
    }

}
