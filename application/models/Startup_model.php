<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Startup_model class
 *
 * @author Victor Rincon
 */
class Startup_model extends CI_Model {
    /**
     * Startup_model constructor.
     */
    public function __construct()
    {
        $time_zone = ( $this->session->has_userdata('hour') ) ? $this->session->userdata('hour') : '-04:00';
        $lc_time_names = ( $this->session->has_userdata('lc_time_names') ) ? $this->session->userdata('lc_time_names') : 'es_US';
        parent::__construct();
        $this->load->database();
        $this->db->query("SET time_zone = '{$time_zone}';");
        $this->db->query("SET lc_time_names = '{$lc_time_names}';");
    }
}
