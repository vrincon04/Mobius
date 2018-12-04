<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 21/06/17
 * Time: 02:23 PM
 */

/**
 * Class Calendar_lib
 */
class Calendar_lib
{
    /**
     * @var CI_Controller
     */
    protected $_CI;

    /**
     * Calendar_lib constructor.
     */
    public function __construct()
    {
        $this->_CI =& get_instance();
    }

    public function calc_end_date($start_date, $duration, $calendar)
    {
        $this->_CI->load->model('calendar_model');
    }

}