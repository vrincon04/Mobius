<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('comments_panel'))
{
    /**
     * @param array $comments
     * @return string
     */
    function comments_panel($comments)
    {
        $_ci =& get_instance();
        return $_ci->load->view('misc/comments', array('comments'=>$comments), TRUE);
    }
}