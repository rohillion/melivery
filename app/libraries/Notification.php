<?php

Class Notification {

    static function send($channel, $event, $message) {
        
        //return true;//TODO. remover esta linea.
        
        $pusher = new Pusher(Config::get('pusher.app_key'), Config::get('pusher.app_secret'), Config::get('pusher.app_id'));

        if ($pusher->get_channel_info($channel)->occupied) {

            return $pusher->trigger($channel, $event, $message);
        }
            
        return false;
    }

}
