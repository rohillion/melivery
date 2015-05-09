<?php

Class CommonEvents {

    static function getLastAction() {

        return Session::get('queue');
    }

    static function setLastAction($route, $postData = NULL) {

        if (!$route)
            return Session::forget('queue');

        return Session::put('queue', [
                    'action' => $route->getActionName(),
                    'post' => $postData
        ]);
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
        for ($i = 0; $i <= 7; $i++) {
            $days[$i] = Lang::get('common.day.' . $type . '.'.$i);
        }
        return $days;
    }

    static function get_tld() {

        $tlds = array();

        preg_match('/[a-z0-9\-]{1,63}(\.[a-z\.]{2,6})$/', Request::root(), $tlds);

        return $tlds; //.com.xx
    }

    static function setLocation() {

        $domainTLD = self::get_tld(); //.com.xx

        $tld = explode('.', substr($domainTLD[1], 1)); //.xx

        if (!Session::get('location'))
            Session::put('location', ['country' => end($tld), 'tld' => $domainTLD[1]]);

        return true;
    }

    static function isSubdomain() {

        $dots = explode('.', Request::root());

        if (count($dots) > 3)
            return true;

        if (count($dots) == 3 && !self::isCountry())
            return true;

        return false;
    }

    static function isCountry() {

        $domainTLD = self::get_tld();

        $tld = explode('.', substr($domainTLD[1], 1)); //.com.xx

        return count($tld) > 1 ? true : false;
    }

    static function payWith($price) {

        $minDenom = 50;
        $maxDenom = 100;

        if ($price == $maxDenom || is_int($price / $maxDenom)) {

            $payWith = false;
        } else if ($price == $minDenom) {

            $payWith = [$maxDenom];
        } else {

            if ($price < $minDenom) {

                $payWith = [$minDenom, $maxDenom];
            } else if ($price > $minDenom && $price < $maxDenom) {

                $payWith = [$maxDenom];
            } else {

                $bills = self::payWith($price - 100);

                foreach ($bills as $bill) {
                    $payWith[] = $bill + 100;
                }
            }
        }

        return $payWith;
    }

    static function humanTiming($time) {

        $time = time() - $time; // to get the time since that moment

        $tokens = array(
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit)
                continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
        }
    }
    
    static function getRandomCode(){
        return str_shuffle(rand(100000, 999999));
    }

}
