<?php

Validator::extend('alpha_spaces', function($attribute, $value) {
    return preg_match('/^[\pL\s]+$/u', $value);
});

Validator::extend('time', function($attribute, $value) {
    //return preg_match('/^([01]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]|60)$/', $value);
    return preg_match('/^([01]?[0-9]|2[0-3]):([0-5][0-9])$/', $value);
});

Validator::extend('minutes', function($attribute, $value) {
    return preg_match('/^([01]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]|60)$/', $value);
    //return preg_match('/^([0-5][0-9]):([0-5][0-9]|60)$/', $value);
});

Validator::extend('price', function($attribute, $value) {
    return preg_match('/^\d+([\,]\d+)*([\.]\d+)?$/', $value);
});