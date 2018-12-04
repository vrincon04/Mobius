<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('dump'))
{
    /**
     * @param mixed $var
     * @param bool $exit
     */
    function dump($var, $exit = FALSE)
    {
        if ( ENVIRONMENT !== 'development' ) { return; }
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        if ( $exit === TRUE ) { exit(); }
    }
}
