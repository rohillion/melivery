<?php

Class SMS {

    static function send($to, $from, $message) {
        
        $http = new Services_Twilio_TinyHttp(//TODO. Remover en produccion.
                'https://api.twilio.com', array('curlopts' => array(
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 2,
        )));

        $client = new Services_Twilio(Config::get('twilio.TWILIO_ACCOUNT_SID'), Config::get('twilio.TWILIO_AUTH_TOKEN'), NULL, $http);

        try {

            $res = $client->account->messages->create(array(
                'To' => $to,
                'From' => $from,
                'Body' => $message,
            ));
            
        } catch (Services_Twilio_RestException $e) {

            $res = $e->getMessage();//TODO. Log.
        }

        return $res;
    }
}
