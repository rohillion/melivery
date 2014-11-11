<?php

Class CommonEvents {

    static function getLastActionUrl() {

        $queue = Session::get('queue');

        if ($queue)
            Session::forget('queue');

        return $queue;
    }

    static function setLastAction($route) {

        return Session::put('queue', ['action' => $route->getActionName()]);
    }

    /*
     *
     * @Create an HTML drop down menu
     *
     * @param string $name The element name and ID
     *
     * @param int $selected The day to be selected
     *
     * @return string
     *
     */

    static function dayDropdown($name = "day", $selected = null) {

        $wd = '<select name="' . $name . '" id="' . $name . '">';

        $days = $this->dayArray();

        /*         * * the current day ** */
        $selected = is_null($selected) ? date('N', time()) : $selected;

        for ($i = 1; $i <= 7; $i++) {
            $wd .= '<option value="' . $i . '"';
            if ($i == $selected) {
                $wd .= ' selected';
            }
            /*             * * get the day ** */
            $wd .= '>' . $days[$i] . '</option>';
        }

        $wd .= '</select>';
        return $wd;
    }

    static function dayArray($type = 'long') {
        return array(
            1 => Lang::get('common.day.' . $type . '.1'),
            2 => Lang::get('common.day.' . $type . '.2'),
            3 => Lang::get('common.day.' . $type . '.3'),
            4 => Lang::get('common.day.' . $type . '.4'),
            5 => Lang::get('common.day.' . $type . '.5'),
            6 => Lang::get('common.day.' . $type . '.6'),
            7 => Lang::get('common.day.' . $type . '.7'),
            8 => Lang::get('common.day.' . $type . '.8')
        );
    }

    static function get_tld() {

        $tlds = array();

        preg_match('/[a-z0-9\-]{1,63}(\.[a-z\.]{2,6})$/', Request::root(), $tlds);

        return $tlds; //.com.xx
    }

    static function setLocation() {

        $domainTLD = CommonEvents::get_tld();

        $host = $domainTLD[0]; //melivery.com.xx

        $tld = explode('.', substr($domainTLD[1], 1));

        // Setup domain configuration
        Config::set('app.url', 'http://' . $host);
        Config::set('app.domain', $host); // An additional configuration apart from app.url
        Config::set('session.domain', '.' . $host); // Make sure that sessions will work on the domain

        if (!Session::get('location')) {
            Session::put('location', ['country' => end($tld), 'tld' => $domainTLD[1]]);
        }

        return NULL;
    }

}
