<?php

function getOrDefault(&$var, $default){
    return isset($var)? $var : $default;
}