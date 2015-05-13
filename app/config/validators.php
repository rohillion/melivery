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

Validator::extend('mobile_ie', function($attribute, $value) {
    return preg_match('/^\+353\d{2}[0-9]{7}/', $value);
});

Validator::extend('mobile_ar', function($attribute, $value) {
    return preg_match('/^\+54[1-9]{1}[0-9]{9}$/', $value);
});

//Mobile 12 numbers
/*Validator::extend('mobile', function($attribute, $value) {
    return preg_match('/^\+\d{3}\d{2}[0-9]{7}/', $value);
});*/