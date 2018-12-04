<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Avatar
 */
class Avatar {

    /**
     * CI Singleton
     *
     * @var	object
     */
    protected $_CI;

    /**
     * @var array
     */
    protected $options;

    /**
     * Avatar constructor.
     */
    public function __construct()
    {
        $this->_CI =& get_instance();

        $this->_CI->load->library('image_lib');
        $this->_CI->load->library('upload');
    }

    /**
     * @param array $options
     * @return bool
     * @throws Exception
     */
    public function create($options = array())
    {

        $default_options = array(
            'file_input'        => 'picture_file',
            'upload_path'       => './public/upload',
            'max_file_size'     => 5120,                        // 5120 KB = 5 MB
            'allowed_types'     => 'jpg|jpeg',
            'quality'           => '100%',
            'avatar_size'       => 200,                         // [200 x 200] px, same height and width
            //'avatar_path'         => './public/img'
        );

        $this->options = array_merge($default_options, $options);

        if ( ! isset($this->options['avatar_path']) )
        {
            throw new Exception('Parameter "avatar_path" is missing.', 1);
        }

        $config = array(
            'upload_path'		=> $this->options['upload_path'],
            'allowed_types'		=> $this->options['allowed_types'],
            'max_size'		    => $this->options['max_file_size'],
            'file_name'			=> 'avatar_'.date('YmdHis').'_'.md5($this->options['avatar_path']),
            'overwrite'			=> TRUE
        );

        $this->_CI->upload->initialize($config);
        if ( !$this->_CI->upload->do_upload($this->options['file_input']))
        {
            throw new Exception($this->_CI->upload->display_errors(), 1);
        }

        $data = $this->_CI->upload->data();

        if ( /*!$this->_crop($data) ||*/ !$this->_resize($data) )
        {
            return FALSE;
        }

        if ( !rename($data['full_path'], $this->options['avatar_path']) )
        {
            throw new Exception('Error moving the file.', 1);
        }

        return TRUE;
    }

    /**
     * @param $data
     * @return bool
     * @throws Exception
     */
    protected function _crop($data)
    {
        $crop = abs($data['image_width'] - $data['image_height']);

        $config = array(
            'image_library'		=> 'gd2',
            'source_image'		=> $data['full_path'],
            'maintain_ratio'	=> FALSE,
            'width'				=> $data['image_width'],
            'height'			=> $data['image_height'],
            'quality'			=> $this->options['quality']
        );

        if ( $data['image_width'] > $data['image_height'] )
        {
            $config['width'] -= $crop;
            $config['x_axis'] = $crop / 2;
        }
        elseif ( $data['image_width'] < $data['image_height'] )
        {
            $config['height'] -= $crop;
            $config['y_axis'] = $crop / 2;
        }
        else
        {
            return TRUE;
        }

        $this->_CI->image_lib->initialize($config);

        if ( ! $this->_CI->image_lib->crop() )
        {
            throw new Exception($this->_CI->image_lib->display_errors(), 1);
        }

        return TRUE;
    }

    /**
     * @param $data
     * @return bool
     * @throws Exception
     */
    protected function _resize($data)
    {
        $config = array(
            'image_library'		=> 'gd2',
            'source_image'		=> $data['full_path'],
            'maintain_ratio'	=> TRUE,
            'width'				=> $this->options['avatar_size'],
            'height'			=> $this->options['avatar_size'],
            'quality'			=> $this->options['quality']
        );

        $this->_CI->image_lib->initialize($config);

        if ( ! $this->_CI->image_lib->resize() )
        {
            throw new Exception($this->_CI->image_lib->display_errors(), 1);
        }

        return TRUE;
    }

}