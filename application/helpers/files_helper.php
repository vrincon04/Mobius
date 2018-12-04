<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('file_icon'))
{
    /**
     * @param string $file_name
     * @param string $resolution
     * @return string
     */
    function file_icon($file_name, $resolution = '16px')
    {
        $path = 'public/img/filetype_icons';
        $default_icon = '_blank.png';

        $ext = file_extension($file_name);

        $icon = "./{$path}/{$resolution}/{$ext}.png";
        if ( file_exists($icon) ) { return $icon; }

        $icon = "./{$path}/{$resolution}/{$default_icon}.png";
        if ( file_exists($icon) ) { return $icon; }

        $icon = "./{$path}/16px/{$default_icon}.png";
        if ( file_exists($icon) ) { return $icon; }

        return '';
    }
}

if ( ! function_exists('file_extension'))
{
    /**
     * @param string $file_name
     * @return string
     */
    function file_extension($file_name)
    {
        $temp = explode('.', trim($file_name));
        $temp = (count($temp) > 1) ? strtolower(end($temp)) : '';
        return $temp;
    }
}