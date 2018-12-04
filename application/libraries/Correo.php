<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Correo
 */
class Correo {

    protected $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('email');
    }

    /**
     * @param $options
     * @return bool
     */
    public function send($options)
    {
        if ( !is_array($options) || count($options) < 1 ) { return FALSE; }

        try
        {
            $this->CI->email->from('notificaciones@ergotec.com.do', 'ERGOSPACE');
            $this->CI->email->to($options['to']);

            if ( isset($options['cc']) ) { $this->CI->email->cc($options['cc']); }
            if ( isset($options['bcc']) ) { $this->CI->email->cc($options['bcc']); }

            $this->CI->email->subject( $options['subject'] );

            if ( isset($options['content']) )
            {
                $data = isset($options['data']) ? $options['data'] : array();
                $data['content'] = $options['content'];
                $message = $this->CI->load->view('email/template', $data, TRUE);
            }
            else
            {
                if ( !isset($options['message']) ) { return FALSE; }
                $message = $options['message'];
            }

            $this->CI->email->message($message);
            if ( isset($options['alt_message']) )
            { $this->CI->email->set_alt_message($options['alt_message']); }
            else
            { $this->CI->email->set_alt_message('Intente abrir este mensaje con un cliente de correo que soporte HTML.'); }

            if ( isset($options['attach']) )
            {                
                foreach ($options['attach'] as $archivo)
                { $this->CI->email->attach($archivo); }
            }

            if ( isset($options['stringAttach']) )
            {
                foreach ($options['stringAttach'] as $stringAttach)
                {
                    $this->CI->email->attach($stringAttach);
                }
            }

            $result = $this->CI->email->send();

            //$debug = $this->CI->email->print_debugger();
            //var_dump($debug);

            return $result;

        }
        catch (Exception $e)
        {
            return FALSE;
            //exit($e->getMessage());
        }
    }

    /**
     * @param $options
     * @return bool
     */
    public function preview($options)
    {
        if ( !is_array($options) || count($options) < 1 ) { return FALSE; }

        if ( isset($options['content']) )
        {
            $data = isset($options['data']) ? $options['data'] : array();
            $data['content'] = $options['content'];
            $message = $this->CI->load->view('email/template', $data, TRUE);
        }
        else
        {
            if ( !isset($options['message']) ) { return FALSE; }
            $message = $options['message'];
        }

        return $message;
    }

}
