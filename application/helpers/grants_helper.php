<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('grant_access'))
{
    /**
     * @param string $controller
     * @param string $method
     * @return bool
     */
    function grant_access($controller, $method)
    {

        //if ( $controller === 'template' ) { return TRUE; }

        if ( $controller !== 'welcome' && $method === 'index' ) { $method = 'view'; }

        switch ($method)
        {
            case 'create':
            case 'view':
            case 'edit':
            case 'delete':
                return isset($_SESSION['grants'][$controller][$method]);
                break;
            default:
                return TRUE;
        }
    }
}

if ( ! function_exists('grant_show'))
{
    /**
     * @param string $controller
     * @param string $method
     * @return string
     */
    function grant_show($controller, $method)
    {
        return (grant_access($controller, $method)) ? '' : 'hide';
    }
}
