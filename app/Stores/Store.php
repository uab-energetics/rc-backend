<?php
/**
 * Created by IntelliJ IDEA.
 * User: chris
 * Date: 12/8/17
 * Time: 10:29 PM
 */

namespace App\Stores;


class Store
{
    static function get(&$var, $default){
        return isset($var)? $var : $default;
    }
}