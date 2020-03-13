<?php

namespace App\Http\Requests;

trait LatLngRulesTrait {

    /*
     * lat lng validation
     * src: https://stackoverflow.com/questions/3518504/regular-expression-for-matching-latitude-longitude-coordinates
     * */

    public function latRegexRule()
    {
        return 'regex:/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/';
    }

    public function lngRegexRule()
    {
        return 'regex:/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/';
    }
}
