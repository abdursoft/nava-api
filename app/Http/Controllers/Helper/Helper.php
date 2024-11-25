<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Helper extends Controller
{
    /**
     * random string generator
     * @param length need a integer length for the random string
     */
    public static function generateRandomString( $length = 6 ) {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen( $characters );
        $randomString     = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $randomString .= $characters[rand( 0, $charactersLength - ( $length - 1 ) )];
        }
        return $randomString;
    }

    /**
     * random number generator
     * @param length need a integer length for the random number
     */
    public static function generateRandomNumber( $length = 6 ) {
        $characters       = '0123456789';
        $charactersLength = strlen( $characters );
        $randomString     = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $randomString .= $characters[rand( 0, $charactersLength - ( $length - 1 ) )];
        }
        return $randomString;
    }
}
