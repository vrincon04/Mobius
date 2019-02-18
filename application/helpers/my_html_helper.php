<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('img_src'))
{
    /**
     * @param string $src
     * @param string $default
     * @return string
     */
    function img_src($src, $default = 'default.jpg')
    {
        if ( !file_exists($src) )
        {
            $src = (file_exists(dirname($src).'/'.$default)) ? dirname($src).'/'.$default : '';
        }

        $src = ($src !== '') ? base_url(resource_link($src)) : '';

        return $src;
    }
}

if ( ! function_exists('alert_link'))
{
    /**
     * @param string $text
     * @param string $url
     * @return string
     */
    function alert_link($text, $url)
    {
        return "<a href='{$url}' class='alert-link'>{$text}</a>";
    }
}

if ( ! function_exists('sidebar_item'))
{
    /**
     * @param string $url
     * @param string $text
     * @param string $icon
     * @param string $color
     * @return string
     */
    function sidebar_item($url, $text, $icon, $color = '', $isMaterial = true)
    {
        $r = explode('/', trim($url),1);
        $r = $r[0];

        $url = base_url($url);

        if ($isMaterial)
            return "<li data-controller='{$r}'><a href='{$url}' class='waves-effect waves-block'><i class='material-icons {$color}'>{$icon}</i><span>{$text}</span></a></li>";
        else
            return "<li data-controller='{$r}'><a href='{$url}' class='waves-effect waves-block'><i class='{$icon} {$color}'></i><span>{$text}</span></a></li>";
    }
}

if ( ! function_exists('resource_link'))
{
    function resource_link($uri)
    {
        $filename = './'.$uri;
        if ( !file_exists($filename) ) { return $uri; }

        return $uri.'?'.filemtime($filename);
    }
}
